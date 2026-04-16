<?php

namespace App\Filament\Resources\WhatsappBroadcastHistoryResource\Pages;

use App\Filament\Resources\WhatsappBroadcastHistoryResource;
use App\Models\Member;
use App\Models\WhatsappBroadcastHistory;
use App\Services\FonteService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateWhatsappBroadcastHistory extends CreateRecord
{
    protected static string $resource = WhatsappBroadcastHistoryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $targetType = $data['target_type'] ?? 'all';
        $memberIds = $data['selected_members'] ?? [];

        $membersQuery = Member::query()
            ->where('type', 'member')
            ->whereNotNull('whatsapp')
            ->where('whatsapp', '!=', '');

        if ($targetType === 'selected') {
            if (empty($memberIds)) {
                throw ValidationException::withMessages([
                    'selected_members' => 'Pilih minimal satu nomor tujuan.',
                ]);
            }

            $membersQuery->whereIn('id', $memberIds);
        }

        $members = $membersQuery->get();

        if ($members->isEmpty()) {
            throw ValidationException::withMessages([
                'selected_members' => 'Tidak ada nomor WhatsApp valid untuk target yang dipilih.',
            ]);
        }

        $batchId = (string) Str::uuid();
        $sentAt = now();
        $creatorId = auth()->id();
        $messageText = "{$data['judul_pesan']}\n\n{$data['isi_pesan']}";

        $firstRecord = null;
        $successCount = 0;
        $failedCount = 0;

        foreach ($members as $member) {
            $phone = $this->normalizePhone($member->whatsapp);

            if (blank($phone)) {
                $record = WhatsappBroadcastHistory::create([
                    'batch_id' => $batchId,
                    'judul_pesan' => $data['judul_pesan'],
                    'isi_pesan' => $data['isi_pesan'],
                    'target_type' => $targetType,
                    'member_id' => $member->id,
                    'no_whatsapp' => (string) $member->whatsapp,
                    'status' => 'failed',
                    'error_message' => 'Nomor WhatsApp tidak valid.',
                    'created_by' => $creatorId,
                    'sent_at' => $sentAt,
                ]);

                $failedCount++;
                $firstRecord ??= $record;
                continue;
            }

            $result = app(FonteService::class)->sendMessage($phone, $messageText);
            $status = $result['success'] ? 'success' : 'failed';

            $record = WhatsappBroadcastHistory::create([
                'batch_id' => $batchId,
                'judul_pesan' => $data['judul_pesan'],
                'isi_pesan' => $data['isi_pesan'],
                'target_type' => $targetType,
                'member_id' => $member->id,
                'no_whatsapp' => $phone,
                'status' => $status,
                'error_message' => $result['success'] ? null : ($result['message'] ?? 'Gagal mengirim pesan'),
                'response_body' => isset($result['response']) ? json_encode($result['response']) : null,
                'created_by' => $creatorId,
                'sent_at' => $sentAt,
            ]);

            if ($result['success']) {
                $successCount++;
            } else {
                $failedCount++;
            }

            $firstRecord ??= $record;
        }

        Notification::make()
            ->title('Broadcast selesai diproses')
            ->body("Batch: {$batchId} | Berhasil: {$successCount} | Gagal: {$failedCount}")
            ->success()
            ->send();

        return $firstRecord;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $normalized = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($normalized, '0')) {
            $normalized = '62' . substr($normalized, 1);
        } elseif (str_starts_with($normalized, '8')) {
            $normalized = '62' . $normalized;
        }

        if (!str_starts_with($normalized, '62')) {
            return null;
        }

        return strlen($normalized) >= 10 ? $normalized : null;
    }
}

