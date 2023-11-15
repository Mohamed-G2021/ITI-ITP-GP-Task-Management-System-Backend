<!DOCTYPE html>
<html>

<head>
        <title>Invitation to Join TaskFlow</title>
</head>

<body>
        <p>Hello dear,</p>

        <p>You have been invited to join TaskFlow. Please click the following link to accept the invitation:</p>

        <a href="{{route('accept-invitation', $invitation->id)}}">Accept Invitation</a>

        <p>If you do not want to accept the invitation, you can decline it by clicking the following link:</p>

        <a href="{{route('decline-invitation', $invitation->id)}}">Decline Invitation</a>

        <p>Thank you!</p>
</body>

</html>