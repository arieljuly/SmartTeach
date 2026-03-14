<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Generated Lesson Materials</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4f46e5;
        }
        .header h1 {
            color: #4f46e5;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background: #4f46e5;
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .activity-item, .question-item, .task-item {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9fafb;
            border-left: 4px solid #4f46e5;
        }
        .item-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
            color: #1f2937;
        }
        .item-description {
            color: #4b5563;
            font-size: 14px;
        }
        .meta-info {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
        .options {
            margin-top: 10px;
            padding-left: 20px;
        }
        .option-item {
            margin: 5px 0;
            font-size: 13px;
        }
        .correct {
            color: #10b981;
            font-weight: bold;
        }
        .rubric-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .rubric-table th {
            background: #4f46e5;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }
        .rubric-table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 12px;
        }
        .rubric-table tr:nth-child(even) {
            background: #f9fafb;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Generated Lesson Materials</h1>
        <p>Based on: {{ $lessonPlan->file_name }}</p>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    @if($type == 'complete' || $type == 'activities')
    <!-- Activities Section -->
    <div class="section">
        <div class="section-title">Classroom Activities</div>
        @forelse($lessonPlan->analysis->activities as $activity)
        <div class="activity-item">
            <div class="item-title">{{ $activity->activity_name }}</div>
            <div class="item-description">{{ $activity->activity_description }}</div>
            <div class="meta-info">Estimated Duration: {{ $activity->estimated_duration }} minutes</div>
        </div>
        @empty
        <p>No activities generated.</p>
        @endforelse
    </div>
    @endif

    @if($type == 'complete' || $type == 'questions')
    <!-- Questions Section -->
    <div class="section">
        <div class="section-title">Assessment Questions</div>
        @php
            $groupedQuestions = $lessonPlan->analysis->questions->groupBy('question_type_id');
        @endphp

        @foreach($groupedQuestions as $typeId => $questions)
            <h3 style="margin: 20px 0 10px 0; color: #4f46e5;">
                {{ $questions->first()->questionType->type_name ?? 'Questions' }}
                <span style="font-size: 14px; color: #6b7280; margin-left: 10px;">
                    ({{ $questions->count() }} items)
                </span>
            </h3>
            
            @foreach($questions as $index => $question)
            <div class="question-item">
                <div class="item-title">Question {{ $index + 1 }} <span style="font-size: 12px; color: #6b7280;">({{ $question->points }} point{{ $question->points > 1 ? 's' : '' }})</span></div>
                <div class="item-description">{{ $question->question_text }}</div>
                
                @if($question->questionType->type_name == 'Multiple Choice')
                <div class="options">
                    @foreach($question->options as $optIndex => $option)
                    <div class="option-item {{ $option->is_correct ? 'correct' : '' }}">
                        {{ chr(65 + $optIndex) }}. {{ $option->option_text }}
                        @if($option->is_correct)
                            <span class="correct">✓ Correct</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        @endforeach
    </div>
    @endif

    @if($type == 'complete' || $type == 'tasks')
    <!-- Performance Tasks Section -->
    <div class="section">
        <div class="section-title">Performance Tasks</div>
        @forelse($lessonPlan->analysis->performanceTasks as $task)
        <div class="task-item">
            <div class="item-title">{{ $task->task_name }}</div>
            <div class="item-description">{{ $task->task_description }}</div>
        </div>
        @empty
        <p>No performance tasks generated.</p>
        @endforelse
    </div>
    @endif

    @if($type == 'complete' || $type == 'rubrics')
    <!-- Rubrics Section -->
    <div class="section">
        <div class="section-title">Assessment Rubrics</div>
        @forelse($lessonPlan->analysis->rubrics as $rubric)
        <div class="task-item">
            <div class="item-title">{{ $rubric->rubric_title }}</div>
            <div class="item-description">Total Score: {{ $rubric->total_score }}</div>
            
            <table class="rubric-table">
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Description</th>
                        <th>Max Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rubric->criterias as $criteria)
                    <tr>
                        <td>{{ $criteria->criteria_name }}</td>
                        <td>{{ $criteria->criteria_description }}</td>
                        <td>{{ $criteria->score }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @empty
        <p>No rubrics generated.</p>
        @endforelse
    </div>
    @endif

    <div class="footer">
        Generated by AI-Powered Lesson Plan Analysis System
    </div>
</body>
</html>