<?php
// fetch_leave.php: Fetch sick leave record by leave code and ID number
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$leaveCode = $_POST['leaveCode'] ?? '';
$idNumber = $_POST['idNumber'] ?? '';

if (!$leaveCode || !$idNumber) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

require_once('../../backend/db.php');

$stmt = $conn->prepare("SELECT * FROM sick_leaves.leave_records WHERE leaveCode = ? AND idNumber = ? LIMIT 1");
$stmt->bind_param('ss', $leaveCode, $idNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'error' => 'لا يوجد سجل بهذا الرقم القومي أو كود الإجازة']);
}

$stmt->close();
$conn->close();
