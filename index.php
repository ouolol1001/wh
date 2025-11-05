<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Students List</h1>

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
</body>
</html>
<script>
    const API_URL = "student.php";

    async function loadstudent(){
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
                `;

                tbody.appendChild(tr); 


         });
    }

    loadstudent();

</script>