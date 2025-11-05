<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 400px;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        form {
            margin-bottom: 10px;
        }
        input {
            padding: 5px;
            margin-right: 10px;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Students List</h1>

    <!-- Add Student Form -->
    <form id="addForm">
        <input type="text" id="name" placeholder="Enter name" required>
        <input type="number" id="age" placeholder="Enter age" required>
        <button type="submit">Add Student</button>
    </form>

    <!-- Student Table -->
    <table id="student">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

<script>
const API_URL = "student.php";

// 载入学生列表
async function loadStudents() {
    const res = await fetch(API_URL);
    const data = await res.json();

    const tbody = document.querySelector('#student tbody');
    tbody.innerHTML = "";

    data.forEach(stu => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${stu.id}</td>
            <td>${stu.name}</td>
            <td>${stu.age}</td>
            <td>
                <button onclick="editStudent(${stu.id}, '${stu.name}', ${stu.age})">✏️ Edit</button>
                <button onclick="deleteStudent(${stu.id})">🗑️ Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// 添加学生
document.querySelector("#addForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const name = document.querySelector("#name").value.trim();
    const age = document.querySelector("#age").value.trim();

    if (!name || isNaN(age)) {
        alert("Please fill in both name and age correctly!");
        return;
    }

    const res = await fetch(API_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, age })
    });

    const result = await res.json();
    alert(result.message || "Failed to add student!");
    document.querySelector("#addForm").reset();
    loadStudents();
});

// 编辑学生 (PUT)
async function editStudent(id, oldName, oldAge) {
    const name = prompt("Enter new name:", oldName);
    const age = prompt("Enter new age:", oldAge);

    if (!name || isNaN(age)) {
        alert("Invalid input!");
        return;
    }

    const res = await fetch(API_URL, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, name, age })
    });

    const result = await res.json();
    alert(result.message || "Failed to update student!");
    loadStudents();
}

// 删除学生 (DELETE)
async function deleteStudent(id) {
    if (!confirm("Are you sure you want to delete this student?")) return;

    const res = await fetch(API_URL, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    });

    const result = await res.json();
    alert(result.message || "Failed to delete student!");
    loadStudents();
}

// 页面加载时载入数据
loadStudents();
</script>


</body>
</html>
