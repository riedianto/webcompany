<?php
declare(strict_types=1);

/* ============================ URL & PATH ============================ */

function base_url(string $path = ''): string
{
    static $base = null;
    if ($base === null) {
        $script = $_SERVER['SCRIPT_NAME'] ?? '/';
        $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
        if (basename($dir) === 'admin') {
            $dir = dirname($dir);
        }
        $base = rtrim($dir, '/');
    }
    return $base . '/' . ltrim($path, '/');
}

function img_url(string $subdir, string $file): string
{
    if ($file === '') {
        return '';
    }
    return base_url('uploads/' . $subdir . '/' . rawurlencode($file));
}

function asset_url(string $file): string
{
    return base_url('assets/' . ltrim($file, '/'));
}

/* ============================ SETTINGS ============================ */

function get_settings(): array
{
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        try {
            foreach (db_all('SELECT setting_key, setting_value FROM settings') as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Throwable $e) {
            $settings = [];
        }
    }
    return $settings;
}

function setting(string $key, string $default = ''): string
{
    $s = get_settings();
    return isset($s[$key]) && $s[$key] !== '' ? (string)$s[$key] : $default;
}

function about_section(string $key): array
{
    $row = db_one('SELECT * FROM about WHERE section_key = ?', [$key]);
    return $row ?: ['title' => '', 'content' => ''];
}

function save_setting(string $key, string $value): void
{
    db_exec(
        'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)',
        [$key, $value]
    );
}

function contact_subjects(): array
{
    $list = preg_split('/\r\n|\r|\n/', setting('contact_subjects')) ?: [];
    $list = array_values(array_filter(array_map('trim', $list), static fn ($s) => $s !== ''));
    if (!$list) {
        $list = ['Umum', 'Pendaftaran & Jadwal Dokter', 'BPJS & Administrasi', 'Lainnya'];
    }
    return $list;
}

function register_url(): string
{
    return setting('register_url', 'https://daftar.rsuarthamedica.co.id');
}

/* ============================ INTERNATIONALIZATION ============================ */

function is_admin_route(): bool
{
    $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    return strpos($script, '/admin/') !== false;
}

function lang_code(): string
{
    static $code = null;
    if ($code === null) {
        $c = $_SESSION['lang'] ?? ($_COOKIE['lang'] ?? 'id');
        $code = ($c === 'en' && !is_admin_route()) ? 'en' : 'id';
    }
    return $code;
}

function t(string $key, array $params = []): string
{
    $text = $GLOBALS['LANG'][$key] ?? $GLOBALS['LANG_ID'][$key] ?? $key;
    return $params ? vsprintf($text, $params) : $text;
}

function lang_url(string $code): string
{
    $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
    $path = (string)parse_url($uri, PHP_URL_PATH);
    $query = (string)parse_url($uri, PHP_URL_QUERY);
    parse_str($query, $params);
    $params['lang'] = $code;
    return $path . '?' . http_build_query($params);
}

/* ============================ OUTPUT ============================ */

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function nl2br_e(?string $value): string
{
    return nl2br(e($value));
}

function truncate(?string $text, int $limit = 120): string
{
    $text = trim(strip_tags((string)$text));
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    return mb_substr($text, 0, $limit) . '…';
}

function format_date_id(?string $date): string
{
    if (!$date) {
        return '-';
    }
    $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $ts = strtotime($date);
    return date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function format_date(?string $date): string
{
    if (lang_code() !== 'en') {
        return format_date_id($date);
    }
    if (!$date) {
        return '-';
    }
    $months = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $ts = strtotime($date);
    return date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function checked(bool $cond): string
{
    return $cond ? 'checked' : '';
}

function selected(bool $cond): string
{
    return $cond ? 'selected' : '';
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = str_replace(['&', ' dan ', '+'], [' dan ', '-', '-'], $text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s_]+/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim((string)$text, '-');
}

function unique_slug(string $title, string $table = 'posts', ?int $exceptId = null): string
{
    $base = slugify($title) ?: 'post-' . date('YmdHis');
    $slug = $base;
    $i = 1;
    while (true) {
        if ($exceptId) {
            $row = db_one("SELECT id FROM {$table} WHERE slug = ? AND id <> ?", [$slug, $exceptId]);
        } else {
            $row = db_one("SELECT id FROM {$table} WHERE slug = ?", [$slug]);
        }
        if (!$row) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}

/* ============================ AUTH & SECURITY ============================ */

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function flash_get(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)$token)) {
        flash('error', t('csrf.expired'));
        redirect($_SERVER['HTTP_REFERER'] ?? base_url('admin/index.php'));
    }
}

function auth_check(): void
{
    if (empty($_SESSION['admin_id'])) {
        redirect(base_url('admin/login.php'));
    }
}

function auth_user(): array
{
    return $_SESSION['admin_user'] ?? [];
}

/* ============================ UPLOAD ============================ */

function upload_image(string $field, string $subdir = 'general', ?string $old = null): array
{
    if (empty($_FILES[$field]) || (int)$_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'file' => $old];
    }

    $result = store_upload($_FILES[$field], $subdir);
    if (!$result['success']) {
        return ['success' => false, 'file' => $old, 'error' => $result['error']];
    }

    $name = $result['name'];
    if ($old && is_file(UPLOAD_DIR . $subdir . DIRECTORY_SEPARATOR . $old)) {
        @unlink(UPLOAD_DIR . $subdir . DIRECTORY_SEPARATOR . $old);
    }

    return ['success' => true, 'file' => $name];
}

function upload_images(string $field, string $subdir = 'general'): array
{
    $saved = [];
    $errors = [];
    if (empty($_FILES[$field]) || !is_array($_FILES[$field]['name'])) {
        return ['success' => true, 'files' => $saved, 'errors' => $errors];
    }

    $count = count($_FILES[$field]['name']);
    for ($i = 0; $i < $count; $i++) {
        $file = [
            'name' => (string)($_FILES[$field]['name'][$i] ?? ''),
            'type' => (string)($_FILES[$field]['type'][$i] ?? ''),
            'tmp_name' => (string)($_FILES[$field]['tmp_name'][$i] ?? ''),
            'error' => (int)($_FILES[$field]['error'][$i] ?? UPLOAD_ERR_NO_FILE),
            'size' => (int)($_FILES[$field]['size'][$i] ?? 0),
        ];
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        $result = store_upload($file, $subdir);
        if ($result['success']) {
            $saved[] = $result['name'];
        } else {
            $errors[] = $result['error'];
        }
    }

    return ['success' => $errors === [], 'files' => $saved, 'errors' => $errors];
}

function store_upload(array $file, string $subdir): array
{
    if ((int)$file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'name' => null, 'error' => 'Terjadi kesalahan saat mengunggah file.'];
    }
    if ((int)$file['size'] > 5242880) {
        return ['success' => false, 'name' => null, 'error' => 'Ukuran file maksimal 5MB.'];
    }

    $ext = strtolower((string)pathinfo((string)$file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
    if (!in_array($ext, $allowed, true)) {
        return ['success' => false, 'name' => null, 'error' => 'Tipe file tidak diizinkan (JPG, PNG, WEBP, GIF, SVG).'];
    }

    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file((string)$file['tmp_name']);
        $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'];
        if (!in_array((string)$mime, $allowedMime, true)) {
            return ['success' => false, 'name' => null, 'error' => 'File bukan merupakan gambar yang valid.'];
        }
    }

    $dir = UPLOAD_DIR . $subdir;
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    $name = date('YmdHis') . '_' . substr(bin2hex(random_bytes(4)), 0, 8) . '.' . $ext;

    if (!move_uploaded_file((string)$file['tmp_name'], $dir . DIRECTORY_SEPARATOR . $name)) {
        return ['success' => false, 'name' => null, 'error' => 'Gagal menyimpan file.'];
    }

    return ['success' => true, 'name' => $name, 'error' => ''];
}

function delete_uploaded(string $subdir, ?string $file): void
{
    if (!$file) {
        return;
    }
    $path = UPLOAD_DIR . $subdir . DIRECTORY_SEPARATOR . $file;
    if (is_file($path)) {
        @unlink($path);
    }
}

/* ============================ REPLY HELPERS ============================ */

function default_reply_email_template(): string
{
    return "Halo {nama},\r\n\r\nTerima kasih telah menghubungi {site}. Pesan Anda dengan subjek \"{subjek}\" sudah kami terima dan akan segera kami tindak lanjuti.\r\n\r\nHormat kami,\r\nTim {site}\r\n{email} | {telp}";
}

function default_reply_wa_template(): string
{
    return 'Halo {nama}, terima kasih sudah menghubungi {site}. Pesan Anda tentang "{subjek}" telah kami terima dan akan segera kami tindak lanjuti.';
}

function reply_email_template(): string
{
    return setting('reply_email_template', default_reply_email_template());
}

function reply_wa_template(): string
{
    return setting('reply_wa_template', default_reply_wa_template());
}

function apply_reply_template(string $template, array $data): string
{
    $placeholders = [
        '{nama}'  => (string)($data['nama'] ?? ''),
        '{subjek}' => (string)($data['subjek'] ?? ''),
        '{pesan}' => (string)($data['pesan'] ?? ''),
        '{site}'  => setting('site_name'),
        '{email}' => setting('site_email'),
        '{telp}'  => setting('site_phone'),
    ];
    return strtr($template, $placeholders);
}

function normalize_wa_number(string $phone): string
{
    $phone = (string)preg_replace('/\D+/', '', $phone);
    if ($phone !== '' && $phone[0] === '0') {
        $phone = '62' . substr($phone, 1);
    }
    return $phone;
}

function gmail_reply_url(string $to, string $subject, string $body): string
{
    return 'https://mail.google.com/mail/?view=cm&fs=1'
        . '&to=' . rawurlencode($to)
        . '&su=' . rawurlencode($subject)
        . '&body=' . rawurlencode($body);
}

function wa_reply_url(string $phone, string $text): string
{
    return 'https://wa.me/' . normalize_wa_number($phone) . '?text=' . rawurlencode($text);
}

/* ============================ DOCTOR SCHEDULE ============================ */

function doctor_days(): array
{
    if (lang_code() === 'en') {
        return [0 => 'Monday', 1 => 'Tuesday', 2 => 'Wednesday', 3 => 'Thursday', 4 => 'Friday', 5 => 'Saturday', 6 => 'Sunday'];
    }
    return [0 => 'Senin', 1 => 'Selasa', 2 => 'Rabu', 3 => 'Kamis', 4 => 'Jumat', 5 => 'Sabtu', 6 => 'Minggu'];
}

function get_doctor_schedules(int $doctorId): array
{
    return db_all('SELECT * FROM doctor_schedules WHERE doctor_id = ? ORDER BY day ASC', [$doctorId]);
}

function get_schedules_map(array $doctorIds): array
{
    $map = [];
    $ids = array_values(array_filter(array_map('intval', $doctorIds), fn($v) => $v > 0));
    if (!$ids) {
        return $map;
    }
    $ph = implode(',', array_fill(0, count($ids), '?'));
    foreach (db_all("SELECT * FROM doctor_schedules WHERE doctor_id IN ($ph) ORDER BY day ASC", $ids) as $row) {
        $map[(int)$row['doctor_id']][] = $row;
    }
    return $map;
}

function format_time(?string $t): string
{
    if (!$t) {
        return '';
    }
    $parts = explode(':', (string)$t);
    return $parts[0] . '.' . ($parts[1] ?? '00');
}

function schedule_compact(array $rows): string
{
    if (!$rows) {
        return '';
    }
    $days = doctor_days();
    $groups = [];
    foreach ($rows as $r) {
        $start = $r['start_time'] ?? null;
        $end = $r['end_time'] ?? null;
        if (!$start || !$end) {
            continue;
        }
        $key = format_time($start) . '–' . format_time($end);
        $groups[$key][] = (int)$r['day'];
    }
    if (!$groups) {
        return '';
    }
    $chunks = [];
    foreach ($groups as $key => $dayList) {
        sort($dayList);
        $ranges = [];
        $i = 0;
        while ($i < count($dayList)) {
            $j = $i;
            while ($j + 1 < count($dayList) && $dayList[$j + 1] === $dayList[$j] + 1) {
                $j++;
            }
            if ($j === $i) {
                $ranges[] = $days[$dayList[$i]];
            } else {
                $ranges[] = $days[$dayList[$i]] . '–' . $days[$dayList[$j]];
            }
            $i = $j + 1;
        }
        $chunks[] = implode(', ', $ranges) . ': ' . $key;
    }
    return implode(' · ', $chunks);
}

function save_doctor_schedules(int $doctorId, array $startByDay, array $endByDay): void
{
    $startByDay = is_array($startByDay) ? $startByDay : [];
    $endByDay = is_array($endByDay) ? $endByDay : [];
    $pdo = db();
    $pdo->beginTransaction();
    try {
        foreach (doctor_days() as $day => $label) {
            $s = trim((string)($startByDay[$day] ?? ''));
            $e = trim((string)($endByDay[$day] ?? ''));
            if ($s !== '' && $e !== '') {
                $stmt = $pdo->prepare('INSERT INTO doctor_schedules (doctor_id, day, start_time, end_time) VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE start_time = VALUES(start_time), end_time = VALUES(end_time)');
                $stmt->execute([$doctorId, $day, $s, $e]);
            } else {
                $stmt = $pdo->prepare('DELETE FROM doctor_schedules WHERE doctor_id = ? AND day = ?');
                $stmt->execute([$doctorId, $day]);
            }
        }
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/* ============================ PAGINATION ============================ */

function paginate(string $sql, array $params, int $perPage = 9): array
{
    $page = max(1, (int)($_GET['page'] ?? 1));
    $countSql = preg_replace('/SELECT\s+.*?\s+FROM/si', 'SELECT COUNT(*) FROM', $sql, 1);
    $total = (int)db_one($countSql, $params)['COUNT(*)'];
    $totalPages = max(1, (int)ceil($total / $perPage));
    $page = min($page, $totalPages);
    $offset = ($page - 1) * $perPage;
    $rows = db_all($sql . ' LIMIT ' . $perPage . ' OFFSET ' . $offset, $params);
    return ['items' => $rows, 'page' => $page, 'total_pages' => $totalPages, 'total' => $total];
}
