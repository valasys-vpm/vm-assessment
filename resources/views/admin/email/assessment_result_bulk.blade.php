<html>

<body>
<table border="1">
    <thead>
        <tr>
            <th rowspan="2">Employee Name</th>
            <th rowspan="2">Employee <br>Code</th>
            <th rowspan="2">Title</th>
            @if(isset($resultAssessments) && !empty($resultAssessments))
                @foreach($resultAssessments as $assessment)
                    <th colspan="2">{{ date('d-M-Y', strtotime($assessment->date)) }}</th>
                @endforeach
            @endif
        </tr>
        <tr>
            @if(isset($resultAssessments) && !empty($resultAssessments))
                @foreach($resultAssessments as $assessment)
                    <th>Score</th>
                    <th>Percentage</th>
                @endforeach
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($results as $user)
        <tr>
            <td>{{ $user->first_name.' '.$user->last_name }}</td>
            <td>{{ $user->employee_code }}</td>
            <td>{{ $user->designation->name }}</td>
            @if(isset($resultAssessments) && !empty($resultAssessments))
                @foreach($resultAssessments as $assessment)
                    @foreach($user->userAssessments as $userAssessment)
                        @if($assessment->id == $userAssessment->assessment_id)
                            <td>{{ $userAssessment->marks_obtained }}</td>
                            <td>{{ ($userAssessment->marks_obtained/$assessment->number_of_questions) * 100 }}%</td>
                        @endif
                    @endforeach
                @endforeach
            @endif

        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
