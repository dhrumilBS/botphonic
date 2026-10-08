<?php
/**
 * roi-pdf-generator.php
 * ─────────────────────────────────────────────────────────────
 * Generates the ROI report PDF using FPDF (no Composer needed).
 *
 * SETUP:
 *  1. Download FPDF from http://www.fpdf.org/  (single fpdf.php file)
 *  2. Place fpdf.php at:
 *     /wp-content/themes/YOUR-THEME/inc/fpdf/fpdf.php
 *  3. That's it — no composer, no server changes.
 *
 * Called by roi-calculator.php → roi_generate_pdf($data)
 * Returns the path to the temporary PDF file, or false on failure.
 * ─────────────────────────────────────────────────────────────
 */

// Load FPDF
$fpdf_path = get_template_directory() . '/inc/fpdf/fpdf.php';
if (file_exists($fpdf_path)) {
	require_once $fpdf_path;
} else {
	error_log('Botphonic ROI: fpdf.php not found at ' . $fpdf_path);
}


function roi_generate_pdf(array $d): string|false
{
	if (!class_exists('FPDF')) {
		error_log('Botphonic ROI: FPDF class not available.');
		return false;
	}

	// ── Format values ─────────────────────────────────────────
	$annual = '$' . number_format($d['annual_save']);
	$monthly = '$' . number_format($d['monthly_save']);
	$pw = number_format($d['payback_weeks'], 1) . ' wks';
	$hrs = number_format($d['hours_saved']);
	$cauto = number_format($d['calls_auto']);
	$hcpc = '$' . number_format($d['human_cpc'], 2);
	$acpc = '$' . number_format($d['ai_cpc'], 2);
	$scpc = '$' . number_format($d['saving_cpc'], 2);
	$date = date('d M Y');

	// ── Colours (R,G,B) ───────────────────────────────────────
	$C_BG = [15, 23, 42];   // #0f172a  dark navy
	$C_CARD = [30, 41, 59];   // #1e293b
	$C_ACCENT = [99, 102, 241];  // #6366f1  indigo
	$C_GREEN = [34, 197, 94];   // #22c55e
	$C_ORANGE = [249, 115, 22];   // #f97316
	$C_WHITE = [255, 255, 255];
	$C_MUTED = [148, 163, 184];  // #94a3b8
	$C_LIGHT = [226, 232, 240];  // #e2e8f0

	// ── Create PDF ────────────────────────────────────────────
	$pdf = new FPDF_Rounded('P', 'mm', 'A4');
	$pdf->SetAutoPageBreak(true, 12);
	$pdf->AddPage();
	$pw_full = $pdf->GetPageWidth();
	$margin = 15;
	$usable = $pw_full - $margin * 2;

	// Helper: fill background
	$pdf->SetFillColor(...$C_BG);
	$pdf->Rect(0, 0, $pw_full, $pdf->GetPageHeight(), 'F');

	// ── HEADER BAR ────────────────────────────────────────────
	$pdf->SetFillColor(...$C_ACCENT);
	$pdf->Rect(0, 0, $pw_full, 28, 'F');

	$pdf->SetFont('Courier', 'B', 16);
	$pdf->SetTextColor(...$C_WHITE);
	$pdf->SetXY($margin, 7);
	$pdf->Cell(0, 8, 'Botphonic AI - ROI Savings Report', 0, 1, 'L');

	$pdf->SetFont('Courier', '', 9);
	$pdf->SetTextColor(...$C_LIGHT);
	$pdf->SetXY($margin, 16);
	$pdf->Cell(
		0,
		6,
		"Prepared for {$d['email']}  |  Generated: {$date}",
		0,
		1,
		'L'
	);

	// ── ANNUAL SAVING HERO ────────────────────────────────────
	$pdf->SetY(36);
	$pdf->SetFont('Courier', '', 10);
	$pdf->SetTextColor(...$C_MUTED);
	$pdf->Cell(
		$usable + $margin * 2,
		6,
		'PROJECTED ANNUAL SAVINGS',
		0,
		1,
		'C'
	);

	$pdf->SetFont('Courier', 'B', 40);
	$pdf->SetTextColor(...$C_GREEN);
	$pdf->Cell($usable + $margin * 2, 18, $annual, 0, 1, 'C');

	$pdf->SetFont('Courier', '', 9);
	$pdf->SetTextColor(...$C_MUTED);
	$pdf->Cell(
		$usable + $margin * 2,
		5,
		'vs. your current human-agent spend',
		0,
		1,
		'C'
	);

	// divider
	$pdf->SetY($pdf->GetY() + 4);
	$pdf->SetDrawColor(30, 41, 59);
	$pdf->Line($margin, $pdf->GetY(), $pw_full - $margin, $pdf->GetY());
	$pdf->SetY($pdf->GetY() + 4);

	// ── 4-METRIC GRID ─────────────────────────────────────────
	$pdf->SetFont('Courier', '', 8);
	$pdf->SetTextColor(...$C_MUTED);
	$pdf->Cell($usable + $margin * 2, 5, 'KEY METRICS', 0, 1, 'C');
	$pdf->SetY($pdf->GetY() + 2);

	$col_w = $usable / 4;
	$start_x = $margin;

	$metrics = [
		['💰', $monthly, 'Monthly Savings', $C_WHITE],
		['⚡', $pw, 'Payback Period', $C_ACCENT],
		['⏱', $hrs, 'Hours Reclaimed/mo', $C_ORANGE],
		['🤖', $cauto, 'Calls Automated/mo', $C_GREEN],
	];

	$cell_h = 22;
	$y_start = $pdf->GetY();
	foreach ($metrics as $i => [$icon, $val, $lbl, $col]) {
		$x = $start_x + $i * ($col_w + 2);
		// card
		$pdf->SetFillColor(...$C_CARD);
		$pdf->SetDrawColor(...$C_CARD);
		$pdf->RoundedRect($x, $y_start, $col_w, $cell_h, 3, 'F');
		// value
		$pdf->SetFont('Courier', 'B', 14);
		$pdf->SetTextColor(...$col);
		$pdf->SetXY($x, $y_start + 5);
		$pdf->Cell($col_w, 7, $val, 0, 0, 'C');
		// label
		$pdf->SetFont('Courier', '', 8);
		$pdf->SetTextColor(...$C_MUTED);
		$pdf->SetXY($x, $y_start + 13);
		$pdf->Cell($col_w, 6, $lbl, 0, 0, 'C');
	}
	$pdf->SetY($y_start + $cell_h + 6);

	// ── COST PER CALL ─────────────────────────────────────────
	$pdf->SetDrawColor(30, 41, 59);
	$pdf->Line($margin, $pdf->GetY(), $pw_full - $margin, $pdf->GetY());
	$pdf->SetY($pdf->GetY() + 4);

	$pdf->SetFont('Courier', '', 8);
	$pdf->SetTextColor(...$C_MUTED);
	$pdf->Cell($usable + $margin * 2, 5, 'COST-PER-CALL COMPARISON', 0, 1, 'C');
	$pdf->SetY($pdf->GetY() + 2);

	$cpc_cols = [
		['Human Agent', $hcpc, $C_ORANGE],
		['AI (Botphonic)', $acpc, $C_ACCENT],
		['You Save', $scpc, $C_GREEN],
	];
	$cw = $usable / 3 - 2;
	$cy = $pdf->GetY();
	$c_h = 20;
	foreach ($cpc_cols as $i => [$lbl, $val, $col]) {
		$cx = $margin + $i * ($cw + 3);
		$pdf->SetFillColor(...$C_CARD);
		$pdf->RoundedRect($cx, $cy, $cw, $c_h, 3, 'F');
		$pdf->SetFont('Courier', '', 8);
		$pdf->SetTextColor(...$C_MUTED);
		$pdf->SetXY($cx, $cy + 3);
		$pdf->Cell($cw, 5, $lbl, 0, 0, 'C');
		$pdf->SetFont('Courier', 'B', 16);
		$pdf->SetTextColor(...$col);
		$pdf->SetXY($cx, $cy + 9);
		$pdf->Cell($cw, 8, $val, 0, 0, 'C');
	}
	$pdf->SetY($cy + $c_h + 6);

	// ── INPUT PARAMETERS TABLE ────────────────────────────────
	$pdf->SetDrawColor(30, 41, 59);
	$pdf->Line($margin, $pdf->GetY(), $pw_full - $margin, $pdf->GetY());
	$pdf->SetY($pdf->GetY() + 4);

	$pdf->SetFont('Courier', '', 8);
	$pdf->SetTextColor(...$C_MUTED);
	$pdf->SetX($margin);
	$pdf->Cell($usable, 5, 'YOUR INPUT PARAMETERS', 0, 1, 'L');
	$pdf->SetY($pdf->GetY() + 2);

	// Header row
	$pdf->SetFillColor(...$C_ACCENT);
	$pdf->SetTextColor(...$C_WHITE);
	$pdf->SetFont('Courier', 'B', 9);
	$pdf->SetX($margin);
	$pdf->Cell($usable * 0.65, 6, 'Parameter', 0, 0, 'L', true);
	$pdf->Cell($usable * 0.35, 6, 'Your Value', 0, 1, 'R', true);

	$params = [
		['Monthly Call Volume', number_format($d['calls'])],
		['Avg. Call Duration', $d['duration'] . ' min'],
		['Number of Human Agents', $d['agents']],
		['Avg. Agent Salary ($/yr)', '$' . number_format($d['salary'])],
		['AI Automation Rate', $d['auto'] . '%'],
	];

	$row_colors = [$C_CARD, [22, 33, 51]];
	foreach ($params as $i => [$label, $value]) {
		[$r, $g, $b] = $row_colors[$i % 2];
		$pdf->SetFillColor($r, $g, $b);
		$pdf->SetTextColor(...$C_LIGHT);
		$pdf->SetFont('Courier', '', 9);
		$pdf->SetX($margin);
		$pdf->Cell($usable * 0.65, 6, $label, 0, 0, 'L', true);
		$pdf->Cell($usable * 0.35, 6, $value, 0, 1, 'R', true);
	}

	$pdf->SetY($pdf->GetY() + 5);

	// ── ROI OVER TIME TABLE ───────────────────────────────────
	if ($pdf->GetY() > 220) {
		$pdf->AddPage();
		$pdf->SetFillColor(...$C_BG);
		$pdf->Rect(0, 0, $pw_full, $pdf->GetPageHeight(), 'F');
	}

	$pdf->SetDrawColor(30, 41, 59);
	$pdf->Line($margin, $pdf->GetY(), $pw_full - $margin, $pdf->GetY());
	$pdf->SetY($pdf->GetY() + 4);
	$pdf->SetFont('Courier', '', 8);
	$pdf->SetTextColor(...$C_MUTED);
	$pdf->SetX($margin);
	$pdf->Cell($usable, 5, 'CUMULATIVE ROI OVER TIME', 0, 1, 'L');
	$pdf->SetY($pdf->GetY() + 2);

	// Header
	$cw4 = $usable / 4;
	$pdf->SetFillColor(...$C_ACCENT);
	$pdf->SetTextColor(...$C_WHITE);
	$pdf->SetFont('Courier', 'B', 9);
	$pdf->SetX($margin);
	foreach (['Month', 'Cumulative Savings', 'AI Cost', 'Net Benefit'] as $h) {
		$pdf->Cell($cw4, 6, $h, 0, 0, 'C', true);
	}
	$pdf->Ln();

	foreach ([1, 3, 6, 9, 12] as $idx => $m) {
		$cum_save = $d['monthly_save'] * $m;
		$cum_ai = $d['ai_cost_monthly'] * $m;
		$net = $cum_save - $cum_ai;

		[$r, $g, $b] = $row_colors[$idx % 2];
		$pdf->SetFillColor($r, $g, $b);
		$pdf->SetTextColor(...$C_LIGHT);
		$pdf->SetFont('Courier', '', 9);
		$pdf->SetX($margin);
		$pdf->Cell($cw4, 6, "Month {$m}", 0, 0, 'C', true);
		$pdf->Cell($cw4, 6, '$' . number_format($cum_save), 0, 0, 'C', true);
		$pdf->Cell($cw4, 6, '$' . number_format($cum_ai), 0, 0, 'C', true);

		// Net benefit — green if positive
		$net_color = $net >= 0 ? $C_GREEN : [239, 68, 68];
		$pdf->SetTextColor(...$net_color);
		$pdf->Cell($cw4, 6, '$' . number_format($net), 0, 0, 'C', true);
		$pdf->SetTextColor(...$C_LIGHT);
		$pdf->Ln();
	}

	// ── FOOTER ────────────────────────────────────────────────
	$pdf->SetY(-20);
	$pdf->SetDrawColor(30, 41, 59);
	$pdf->Line($margin, $pdf->GetY(), $pw_full - $margin, $pdf->GetY());
	$pdf->SetY($pdf->GetY() + 2);
	$pdf->SetFont('Courier', '', 8);
	$pdf->SetTextColor(...$C_MUTED);
	$pdf->Cell(
		0,
		5,
		'Botphonic AI  |  botphonic.com  |  Results are projections based on your inputs.',
		0,
		0,
		'C'
	);

	// ── Save to temp file ─────────────────────────────────────
	$upload_dir = wp_upload_dir();
	$tmp_path = $upload_dir['basedir'] . '/roi-reports/';
	if (!is_dir($tmp_path)) {
		wp_mkdir_p($tmp_path);
		// Protect the folder
		file_put_contents($tmp_path . '.htaccess', 'deny from all');
	}

	$filename = 'roi-report-' . time() . '-' . wp_generate_password(8, false) . '.pdf';
	$filepath = $tmp_path . $filename;

	$pdf->Output('F', $filepath);

	return file_exists($filepath) ? $filepath : false;
}


/**
 * FPDF RoundedRect helper (FPDF doesn't have this natively).
 * Monkey-patch it onto the FPDF instance via a thin subclass.
 *
 * NOTE: If you use TCPDF instead of FPDF, delete this block —
 * TCPDF has RoundedRect built in.
 */
if (class_exists('FPDF') && !method_exists('FPDF', 'RoundedRect')) {
	// We can't retroactively add methods to FPDF, so we override
	// the class used above with a subclass.  The function
	// roi_generate_pdf() references 'new FPDF' — replace that
	// with 'new FPDF_Rounded' if you want rounded corners.
	class FPDF_Rounded extends FPDF
	{
		public function RoundedRect(
			float $x,
			float $y,
			float $w,
			float $h,
			float $r,
			string $style = ''
		): void {
			$k = $this->k;
			$hp = $this->h;
			$op = match (strtoupper($style)) {
				'F' => 'f',
				'FD', 'DF' => 'B',
				default => 'S',
			};
			$MyArc = 4 / 3 * (sqrt(2) - 1);
			$this->_out(sprintf(
				'%.2F %.2F m',
				($x + $r) * $k,
				($hp - $y) * $k
			));
			$xc = $x + $w - $r;
			$yc = $y + $r;
			$this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
			$this->_Arc($xc, $yc, $r, 90, 0);
			$xc = $x + $w - $r;
			$yc = $y + $h - $r;
			$this->_out(sprintf(
				'%.2F %.2F l',
				($x + $w) * $k,
				($hp - $yc) * $k
			));
			$this->_Arc($xc, $yc, $r, 0, -90);
			$xc = $x + $r;
			$yc = $y + $h - $r;
			$this->_out(sprintf(
				'%.2F %.2F l',
				$xc * $k,
				($hp - ($y + $h)) * $k
			));
			$this->_Arc($xc, $yc, $r, -90, -180);
			$xc = $x + $r;
			$yc = $y + $r;
			$this->_out(sprintf(
				'%.2F %.2F l',
				$x * $k,
				($hp - $yc) * $k
			));
			$this->_Arc($xc, $yc, $r, 180, 90);
			$this->_out($op);
		}

		private function _Arc(
			float $x1,
			float $y1,
			float $r,
			float $a1,
			float $a2
		): void {
			$a1 = deg2rad($a1);
			$a2 = deg2rad($a2);
			$k = $this->k;
			$hp = $this->h;
			$MyArc = 4 / 3 * (sqrt(2) - 1);
			$dx = $r * $MyArc;
			$x = $x1 + $r * cos($a1);
			$y = $y1 - $r * sin($a1);
			$x2 = $x1 + $r * cos($a2);
			$y2 = $y1 - $r * sin($a2);
			$cx1 = $x - $dx * sin($a1);
			$cy1 = $y - $dx * cos($a1);
			$cx2 = $x2 + $dx * sin($a2);
			$cy2 = $y2 + $dx * cos($a2);
			$this->_out(sprintf(
				'%.2F %.2F %.2F %.2F %.2F %.2F c',
				$cx1 * $k,
				($hp - $cy1) * $k,
				$cx2 * $k,
				($hp - $cy2) * $k,
				$x2 * $k,
				($hp - $y2) * $k
			));
		}
	}
}
