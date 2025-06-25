<?php
include 'student.php';
$stu = new Student();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $stu->addStudent($_POST['name'], $_POST['email']);
        header("Location: index.php");
        exit;
    } elseif (isset($_POST['edit'])) {
        $stu->editStudent($_POST['id'], $_POST['name'], $_POST['email']);
        header("Location: index.php");
        exit;
    } elseif (isset($_POST['confirm_delete'])) {
        $stu->deleteStudent($_POST['delete_id']);
        header("Location: index.php");
        exit;
    }
}

$editing = isset($_GET['edit']) ? $stu->getStudent($_GET['edit']) : null;
$confirming = isset($_GET['confirm']) && $stu->exists($_GET['confirm']) ? $stu->getStudent($_GET['confirm']) : null;
$showForm = isset($_GET['show']) && $_GET['show'] === 'form' || $editing;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-100 to-white min-h-screen flex items-center justify-center py-10 font-sans">
<div class="w-full max-w-4xl px-4">

  <?php if ($confirming): ?>
    <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded shadow-md max-w-sm w-full text-center">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Delete Student</h2>
        <p class="text-gray-700 mb-6">
          Are you sure you want to delete <span class="font-semibold"><?= htmlspecialchars($confirming['name']) ?></span>?
        </p>
        <form method="POST" class="flex justify-center space-x-4">
          <input type="hidden" name="delete_id" value="<?= $confirming['id'] ?>">
          <button type="submit" name="confirm_delete" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Yes</button>
          <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <a href="?show=form" class="inline-block mb-6 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
    <i class="fas fa-plus mr-2"></i>Add Student
  </a>

  <?php if ($showForm): ?>
    <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-xl mx-auto relative">
        <h2 class="text-2xl font-bold text-blue-700 mb-4"><?= $editing ? 'Edit Student' : 'Add Student' ?></h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">
          <input type="text" name="name" placeholder="Full Name" value="<?= $editing['name'] ?? '' ?>" required class="border p-2 rounded w-full">
          <input type="email" name="email" placeholder="Email Address" value="<?= $editing['email'] ?? '' ?>" required class="border p-2 rounded w-full">
          <div class="md:col-span-2 flex justify-between mt-2">
            <button type="submit" name="<?= $editing ? 'edit' : 'add' ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
              <?= $editing ? 'Update' : 'Add' ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <div class="bg-white shadow-md rounded-lg overflow-hidden">
    <h2 class="text-xl font-semibold text-gray-700 px-6 py-4 border-b">Student List</h2>
    <table class="min-w-full text-sm text-left text-gray-700">
      <thead class="bg-blue-50 text-blue-800">
        <tr>
          <th class="px-6 py-3">ID</th>
          <th class="px-6 py-3">Name</th>
          <th class="px-6 py-3">Email</th>
          <th class="px-6 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($stu->getStudents() as $student): ?>
        <tr class="border-t hover:bg-blue-50 transition">
          <td class="px-6 py-3 font-mono text-sm"><?= str_pad($student['id'], 2, '0', STR_PAD_LEFT) ?></td>
          <td class="px-6 py-3"><?= htmlspecialchars($student['name']) ?></td>
          <td class="px-6 py-3"><?= htmlspecialchars($student['email']) ?></td>
          <td class="px-6 py-3 space-x-2">
            <a href="?edit=<?= $student['id'] ?>" class="text-blue-600 hover:text-blue-800" title="Edit">
              <i class="fas fa-pen-to-square"></i>
            </a>
            <a href="?confirm=<?= $student['id'] ?>" class="text-red-600 hover:text-red-800" title="Delete">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>