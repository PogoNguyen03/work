<?php
// Sử dụng đường dẫn tuyệt đối để tránh lỗi open_basedir
$base_path = realpath(__DIR__ . '/../..');
$autoload_app = $base_path . '/app/vendor/autoload.php';
$autoload_root = $base_path . '/vendor/autoload.php';
if (file_exists($autoload_app)) {
    require_once $autoload_app;
} elseif (file_exists($autoload_root)) {
    require_once $autoload_root;
} else {
    die('Không tìm thấy file autoload.php. Hãy chạy composer install!');
}
// Export Excel helper 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function exportReportsToExcel($reports, $role, $user_id, $department_id) {
    global $conn, $base_path;
    require_once $base_path . '/app/helpers/i18n.php';
    $currentLang = getCurrentLang();
    // Lấy thông tin user
    $stmt = $conn->prepare("SELECT role, name, name_zh, department_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$user) die("Không tìm thấy thông tin người dùng!");
    $name = ($currentLang === 'zh' && !empty($user['name_zh'])) ? $user['name_zh'] : $user['name'];
    // Lấy tên ban
    $department_name = '';
    if ($department_id) {
        $stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
        $stmt->bind_param("i", $department_id);
        $stmt->execute();
        $department = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $department_name = $department ? $department['name'] : '';
    }
    // Tiêu đề file
    switch ($role) {
        case 'user':
            $headers = [__('title'), __('content'), __('created_at'), __('status')];
            $title = __('reports') . ' - ' . $name . ' - ' . $department_name;
            break;
        case 'nhomtruong':
            $headers = [__('reporter'), __('role'), __('title'), __('content'), __('created_at'), __('status')];
            $title = __('reports') . ' - ' . $name . ' - ' . __('team_leader') . ' - ' . $department_name;
            break;
        case 'quanly':
            $headers = [__('reporter'), __('role'), __('title'), __('content'), __('created_at'), __('status')];
            $title = __('reports') . ' - ' . $name . ' - ' . __('manager') . ' - ' . $department_name;
            break;
        case 'admin':
            $headers = [__('reporter'), __('role'), __('department'), __('title'), __('content'), __('created_at'), __('status')];
            $title = __('reports') . ' - ' . $name . ' - Admin - ' . __('all');
            break;
        default:
            die("Không rõ vai trò người dùng.");
    }
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Thiết lập tiêu đề
    $sheet->setCellValue('A1', $title);
    $sheet->mergeCells('A1:' . chr(64 + count($headers)) . '1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4472C4');
    $sheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF');
    // Ghi tiêu đề cột
    $col = 1;
    $row = 3;
    foreach ($headers as $header) {
        $sheet->setCellValue(chr(64 + $col) . $row, $header);
        $col++;
    }
    $headerRange = 'A' . $row . ':' . chr(64 + count($headers)) . $row;
    $sheet->getStyle($headerRange)->getFont()->setBold(true);
    $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
    $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    // Ghi dữ liệu
    $row = 4;
    foreach ($reports as $data) {
        $col = 1;
        // Xác định trạng thái cập nhật
        if ($currentLang === 'zh') {
            $status = __('new');
            if ($data['updated_at'] && $data['updated_at'] != $data['created_at']) {
                if (shouldShowUpdatedBadge($data['id'], $data['updated_at'], $data['created_at'])) {
                    $status = date('d/m/Y H:i', strtotime($data['updated_at'])) . ' ' . __('updated');
                } else {
                    $status = __('viewed');
                }
            }
        } else {
            $status = __('new');
            if ($data['updated_at'] && $data['updated_at'] != $data['created_at']) {
                if (shouldShowUpdatedBadge($data['id'], $data['updated_at'], $data['created_at'])) {
                    $status = date('d/m/Y H:i', strtotime($data['updated_at'])) . ' ' . __('updated');
                } else {
                    $status = __('viewed');
                }
            }
        }
        $titleVal = ($currentLang === 'zh' && !empty($data['title_zh'])) ? $data['title_zh'] : $data['title'];
        $contentVal = ($currentLang === 'zh' && !empty($data['content_zh'])) ? $data['content_zh'] : $data['content'];
        $nameVal = ($currentLang === 'zh' && !empty($data['name_zh'])) ? $data['name_zh'] : $data['name'];
        $roleVal = ($currentLang === 'zh') ? __(roleToVietnamese($data['user_role'])) : roleToVietnamese($data['user_role']);
        $departmentVal = $data['department_name'] ?? '';
        if ($role === 'user') {
            $sheet->setCellValue(chr(64 + $col++).$row, $titleVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $contentVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['created_at']);
            $sheet->setCellValue(chr(64 + $col++).$row, $status);
        } else if ($role === 'nhomtruong' || $role === 'quanly') {
            $sheet->setCellValue(chr(64 + $col++).$row, $nameVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $roleVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $titleVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $contentVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['created_at']);
            $sheet->setCellValue(chr(64 + $col++).$row, $status);
        } else if ($role === 'admin') {
            $sheet->setCellValue(chr(64 + $col++).$row, $nameVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $roleVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $departmentVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $titleVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $contentVal);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['created_at']);
            $sheet->setCellValue(chr(64 + $col++).$row, $status);
        }
        $row++;
    }
    $dataRange = 'A4:' . chr(64 + count($headers)) . ($row - 1);
    $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
    $sheet->getStyle($dataRange)->getAlignment()->setWrapText(true);
    $tableRange = 'A3:' . chr(64 + count($headers)) . ($row - 1);
    $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    foreach (range('A', chr(64 + count($headers))) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    $filename = "baocao_" . strtolower($role) . "_" . date('Ymd_His') . ".xlsx";
    if (ob_get_length()) ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");
    exit;
}

function roleToVietnamese($role) {
    switch ($role) {
        case 'admin': return 'admin';
        case 'quanly': return 'manager';
        case 'nhomtruong': return 'team_leader';
        case 'user': return 'employee';
        default: return 'user';
    }
} 