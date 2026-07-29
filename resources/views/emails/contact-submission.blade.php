<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Merge demo enquiry</title>
</head>
<body style="margin:0;padding:24px;background:#f5f1eb;color:#2f241e;font-family:Arial,sans-serif;">
    <div style="max-width:560px;margin:0 auto;overflow:hidden;border:1px solid #e1d6cc;border-radius:12px;background:#ffffff;">
        <div style="padding:24px;background:#30231d;color:#ffffff;">
            <p style="margin:0 0 6px;color:#e0ad68;font-size:12px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;">Merge</p>
            <h1 style="margin:0;font-size:24px;">New demo enquiry</h1>
        </div>
        <div style="padding:24px;">
            <p style="margin:0 0 16px;line-height:1.6;">A visitor submitted the contact form on the Merge website.</p>
            <p style="margin:0 0 10px;"><strong>Name:</strong> {{ $submission->full_name }}</p>
            <p style="margin:0 0 10px;"><strong>Email:</strong> <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></p>
            <p style="margin:0;"><strong>Telephone:</strong> {{ $submission->telephone }}</p>
        </div>
    </div>
</body>
</html>
