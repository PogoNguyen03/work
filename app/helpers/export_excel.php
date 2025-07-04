<?php
require_once __DIR__ . '/../../../Baocaocongviec/vendor/autoload.php';
// Export Excel helper 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function exportReportsToExcel($reports, $role, $user_id, $department_id) {
    global $conn;
    // Lấy thông tin user
    $stmt = $conn->prepare("SELECT role, name, department_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$user) die("Không tìm thấy thông tin người dùng!");
    $name = $user['name'];
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
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Thiết lập tiêu đề bảng tuỳ vai trò
    switch ($role) {
        case 'user':
            $headers = ['Tiêu đề', 'Nội dung', 'Ngày giờ báo cáo', 'Trạng thái'];
            $title = "BÁO CÁO CÔNG VIỆC - $name - $department_name";
            break;
        case 'nhomtruong':
            $headers = ['Tên người gửi', 'Vai trò', 'Tiêu đề', 'Nội dung', 'Ngày tạo', 'Trạng thái'];
            $title = "BÁO CÁO CÔNG VIỆC - $name - Nhóm trưởng - $department_name";
            break;
        case 'quanly':
            $headers = ['Tên người gửi', 'Vai trò', 'Tiêu đề', 'Nội dung', 'Ngày tạo', 'Trạng thái'];
            $title = "BÁO CÁO CÔNG VIỆC - $name - Quản lý - $department_name";
            break;
        case 'admin':
            $headers = ['Tên người gửi', 'Chức vụ', 'Ban', 'Tiêu đề', 'Nội dung', 'Ngày tạo', 'Trạng thái'];
            $title = "BÁO CÁO CÔNG VIỆC - $name - Admin - Tất cả ban";
            break;
        default:
            die("Không rõ vai trò người dùng.");
    }
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
        $status = 'Mới tạo';
        if ($data['updated_at'] && $data['updated_at'] != $data['created_at']) {
            if (shouldShowUpdatedBadge($data['id'], $data['updated_at'], $data['created_at'])) {
                $status = date('d/m/Y H:i', strtotime($data['updated_at'])) . ' đã cập nhật';
            } else {
                $status = 'Đã xem';
            }
        }
        
        if ($role === 'user') {
            $sheet->setCellValue(chr(64 + $col++).$row, $data['title']);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['content']);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['created_at']);
            $sheet->setCellValue(chr(64 + $col++).$row, $status);
        } else if ($role === 'nhomtruong' || $role === 'quanly') {
            $sheet->setCellValue(chr(64 + $col++).$row, $data['name']);
            $sheet->setCellValue(chr(64 + $col++).$row, roleToVietnamese($data['user_role']));
            $sheet->setCellValue(chr(64 + $col++).$row, $data['title']);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['content']);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['created_at']);
            $sheet->setCellValue(chr(64 + $col++).$row, $status);
        } else if ($role === 'admin') {
            $sheet->setCellValue(chr(64 + $col++).$row, $data['name']);
            $sheet->setCellValue(chr(64 + $col++).$row, roleToVietnamese($data['user_role']));
            $sheet->setCellValue(chr(64 + $col++).$row, $data['department_name']);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['title']);
            $sheet->setCellValue(chr(64 + $col++).$row, $data['content']);
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
        case 'admin': return 'Admin';
        case 'quanly': return 'Quản lý';
        case 'nhomtruong': return 'Nhóm trưởng';
        case 'user': return 'Nhân viên';
        default: return 'Người dùng';
    }
} 