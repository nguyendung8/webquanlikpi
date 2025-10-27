<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .task-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .task-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .task-detail {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .task-detail:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #6c757d;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #2c3e50;
        }
        .submission-box {
            background: #e7f5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">✅ Nhân Viên Đã Nộp Bài</h1>
    </div>

    <div class="content">
        <p>Xin chào <strong>{{ $managerName }}</strong>,</p>

        <p>Nhân viên <strong>{{ $userName }}</strong> đã nộp bài cho nhiệm vụ được phân công. Vui lòng xem xét và phê duyệt.</p>

        <div class="task-info">
            <div class="task-title">{{ $taskName }}</div>

            <div class="task-detail">
                <span class="label">👤 Nhân viên:</span>
                <span class="value">{{ $userName }}</span>
            </div>

            <div class="task-detail">
                <span class="label">📅 Ngày nộp:</span>
                <span class="value">{{ $submitDate }}</span>
            </div>

            @if($hasFile)
            <div class="task-detail">
                <span class="label">📎 File đính kèm:</span>
                <span class="value" style="color: #28a745;">✓ Có file đính kèm</span>
            </div>
            @endif
        </div>

        <div class="submission-box">
            <strong>📝 Minh chứng:</strong>
            <p style="margin-top: 10px; white-space: pre-wrap;">{{ $evidenceText }}</p>
        </div>

        <p>Vui lòng đăng nhập vào hệ thống để xem chi tiết và duyệt bài nộp.</p>

        <center>
            <a href="{{ $loginUrl }}" class="button">Xem Chi Tiết & Duyệt Bài</a>
        </center>

        <div class="footer">
            <p>Email này được gửi tự động từ Hệ thống Quản lý KPI</p>
            <p>Vui lòng không trả lời email này</p>
        </div>
    </div>
</body>
</html>

