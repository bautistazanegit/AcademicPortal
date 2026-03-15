<?php
session_start();
include '../db.php'; 

// Pigilan ang anumang PHP errors na makasira sa JSON response
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// API Configuration
$apiKey = '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'; 
$user_id = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['username'] ?? 'Student';

// --- A. KNOWLEDGE BASE: SYSTEM FUNCTIONS & RULES ---
$system_functions = "
System Name: Nexus Academic Portal.
Main Functions:
- Dashboard (home.php): Summary of Enrolled Subjects and GPA.
- Subjects (subjects.php): List of subjects and assigned teachers.
- View Grades (view_grades.php): Detailed breakdown of Prelim, Midterm, and Finals.
- About Us (about.php): Developed by Jamaica Estrada and her group.

Academic Rules:
- Passing Grade: 3.0 and below (e.g., 1.0, 2.0, 3.0 are PASS).
- Failing Grade: Higher than 3.0 (e.g., 3.1 to 5.0 are FAILED).
- GPA Calculation: Average of (Prelim + Midterm + Finals) / 3.
";

// --- B. LIVE STUDENT DATA (Base sa user_system.sql) ---
$student_context = "No specific data found.";
if ($user_id > 0) {
    try {
        // Query para sa Subjects at Teachers base sa iyong SQL structure
        $sub_res = $conn->query("SELECT subject_code, subject_name, subject_teacher FROM subjects WHERE student_id = $user_id");
        $subject_list = [];
        while($row = $sub_res->fetch_assoc()) { 
            $subject_list[] = $row['subject_code'] . ": " . $row['subject_name'] . " (Teacher: " . $row['subject_teacher'] . ")"; 
        }
        
        // Query para sa Grades base sa iyong SQL structure
        $grade_res = $conn->query("SELECT s.subject_name, g.prelim, g.midterm, g.finals 
                                   FROM grades g 
                                   JOIN subjects s ON g.subject_id = s.id 
                                   WHERE g.student_id = $user_id");
        $grade_info = [];
        while($g = $grade_res->fetch_assoc()) {
            $grade_info[] = "{$g['subject_name']}: Prelim({$g['prelim']}), Midterm({$g['midterm']}), Finals({$g['finals']})";
        }

        $student_context = "User: $username. Enrolled: " . implode(", ", $subject_list) . ". Grade Records: " . implode("; ", $grade_info);
    } catch (Exception $e) {
        $student_context = "Database access error.";
    }
}

// --- C. AI BRAIN PROCESSING ---
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$payload = [
    'model' => 'llama-3.1-8b-instant', 
    'messages' => [
        [
            'role' => 'system', 
            'content' => "Ikaw ang Nexus AI Assistant.
            
            RULES:
            1. NAVIGATION: Kapag tinanong kung PAANO makita ang grades/subjects, ituro ang sidebar links: 'View Grades' o 'Subjects'.
            2. TEACHERS: Gamitin ang data para sabihin kung sino ang instructor sa isang subject (halimbawa: IPT101 is Vladimir Figueroa).
            3. GRADES: Gamitin ang logic na 3.0 and below is PASSING.
            4. CREATORS: Ang system na ito ay gawa ni Zane Bautista at ng kanyang grupo.
            5. CONTEXT: Gamitin ang data na ito: $student_context.
            6. MESSAGE: kapag nag hello sayo ang user sabihin mo (e.g., 'Hello, how are you?').
            
            Format: Maging conversational at magalang."
        ],
        ['role' => 'user', 'content' => $userMessage]
    ],
    'temperature' => 0.5
];

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$response = curl_exec($ch);
curl_close($ch);

if (ob_get_length()) ob_clean();
echo $response;