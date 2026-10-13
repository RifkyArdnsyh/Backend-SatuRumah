<?php

namespace App\Services;

use App\Models\Setting;

/**
 * Pembaca durasi SLA yang dinamis (database -> .env -> nilai bawaan config).
 * Perhitungan hari kerja dan hari libur nasional dibuat terpisah pada tahap berikutnya.
 */
class SlaSettings
{
    /** Batas SLA (hari kerja) untuk tahap 2-5; tahap lain tidak punya SLA. */
    public function daysForStage(int $stage): ?int
    {
        if ($stage < 2 || $stage > 5) {
            return null;
        }

        return Setting::getInt("sla.t{$stage}_days");
    }

    /** Persentase batas waktu saat label "Mendekati Batas" aktif. */
    public function warningPercent(): int
    {
        return Setting::getInt('sla.warning_percent', 80);
    }

    /** Batas perbaikan oleh pengembang (hari kalender). */
    public function revisionDeadlineDays(): int
    {
        return Setting::getInt('revision.deadline_days', 14);
    }
}
