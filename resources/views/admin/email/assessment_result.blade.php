<html>

<body>
Hi Sir,
<br><br>
Please find the results for the assessment conducted on {{ date('d-M-Y', strtotime($resultAssessment->date)) }}:
<br><br>
<table border="1">
    <thead>
        <th>Employee Name</th>
        <th>Employee <br>Code</th>
        <th>Title</th>
        <th>Score</th>
        <th>Percentage</th>
    </thead>
    <tbody>
    <tr>
        @foreach($resultUserAssessment as $userAssessment)
        <td>{{ $userAssessment->user->first_name.' '.$userAssessment->user->last_name }}</td>
        <td>{{ $userAssessment->user->employee_code }}</td>
        <td>{{ $userAssessment->user->designation->name }}</td>
        <td>{{ $userAssessment->marks_obtained }}</td>
        <td>{{ ($userAssessment->marks_obtained/$resultAssessment->number_of_questions) * 100 }}%</td>
        @endforeach
    </tr>
    </tbody>
</table>
</body>
</html>
