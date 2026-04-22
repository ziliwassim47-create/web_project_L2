<?php
session_start();

if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'delete_user') {
        $id = (int)$_POST['user_id'];
        $conn->query("DELETE FROM users WHERE id = $id");
        header("Location: admin_dashboard.php?success=user_deleted");
        exit;
    }

    if ($action === 'delete_quiz') {
        $id = (int)$_POST['quiz_id'];
        $conn->query("DELETE FROM questions WHERE quiz_id = $id");
        $conn->query("DELETE FROM quizzes WHERE id = $id");
        header("Location: admin_dashboard.php?success=quiz_deleted");
        exit;
    }

    if ($action === 'add_quiz') {
        $title = $conn->real_escape_string($_POST['title']);
        $theme = $conn->real_escape_string($_POST['theme']);
        $conn->query("INSERT INTO quizzes (title, theme) VALUES ('$title', '$theme')");
        header("Location: admin_dashboard.php?success=quiz_added");
        exit;
    }

    if ($action === 'delete_question') {
        $id = (int)$_POST['question_id'];
        $quiz_id = (int)$_POST['quiz_id'];
        $conn->query("DELETE FROM questions WHERE id = $id");
        header("Location: admin_questions.php?quiz_id=$quiz_id&success=question_deleted");
        exit;
    }

    if ($action === 'add_question') {
        $quiz_id = (int)$_POST['quiz_id'];
        $text = $conn->real_escape_string($_POST['question_text']);
        $a1 = $conn->real_escape_string($_POST['answer1']);
        $a2 = $conn->real_escape_string($_POST['answer2']);
        $a3 = $conn->real_escape_string($_POST['answer3']);
        $a4 = $conn->real_escape_string($_POST['answer4']);
        $correct = (int)$_POST['correct_answer'];

        $conn->query("INSERT INTO questions (quiz_id, question_text, answer1, answer2, answer3, answer4, correct_answer) VALUES ($quiz_id, '$text', '$a1', '$a2', '$a3', '$a4', $correct)");
        header("Location: admin_questions.php?quiz_id=$quiz_id&success=question_added");
        exit;
    }
}

header("Location: admin_dashboard.php");
exit;
