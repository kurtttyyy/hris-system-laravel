<h2>Your are now schedule for an Interview</h2>

<p>Hello,</p>

<p>Your interview schedule date:</p> <strong>{{ $interview->date }}</strong>
<p>Your interview schedule time:</p> <strong>{{ $interview->time }}</strong>
<p>Your interview schedule duration:</p> <strong>{{ $interview->duration }}</strong>
<p>Your interview schedule interviewers:</p> <strong>{{ $interview->interviewers }}</strong>
@if (empty($interview->email_link))
@else
<p>Email Link:</p> <strong>{{ $interview->email_link }}</strong>
@endif

@if (empty($interview->url))
@else
<p>Url:</p> <strong>{{ $interview->url }}</strong>
@endif

@if (empty($interview->notes))
@else
<p>Notes:</p> <strong>{{ $interview->notes }}</strong>
@endif
<p>Thank you.</p>
