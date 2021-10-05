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
            <th colspan="2">Average</th>
        </tr>
        <tr>
            @if(isset($resultAssessments) && !empty($resultAssessments))
                @foreach($resultAssessments as $assessment)
                    <th>Score</th>
                    <th>Percentage</th>
                @endforeach
            @endif
            <th>Total Score</th>
            <th>Average Percentage</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $user)
            @php
                $totalMarks = 0;
                $totalMarksObtained = 0;
            @endphp
        <tr>
            <td>{{ $user->first_name.' '.$user->last_name }}</td>
            <td>{{ $user->employee_code }}</td>
            <td>{{ $user->designation->name }}</td>
            @if(isset($resultAssessments) && !empty($resultAssessments))
                @foreach($resultAssessments as $assessment)
                    @forelse$user->userAssessments as $userAssessment)
                        @if($assessment->id == $userAssessment->assessment_id)
                            @php
                                $totalMarksObtained = $totalMarksObtained + $userAssessment->marks_obtained;
                                $totalMarks = $totalMarks + $assessment->number_of_questions;
                            @endphp
                            <td>{{ $userAssessment->marks_obtained }}</td>
                            <td>{{ ($userAssessment->marks_obtained/$assessment->number_of_questions) * 100 }}%</td>
                        @endif
                    @empty
                        <td style="background: red;"><strong>NA</strong></td>
                        <td style="background: red;"><strong>NA</strong></td>
                    @endforeach
                @endforeach
            @endif
            <td>{{ $totalMarksObtained }}</td>
            <td>{{ ($totalMarksObtained/$totalMarks) * 100 }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
