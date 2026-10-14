@extends('emails.layout')
@section('content')
<h2 style="color:#0B1E3E;font-size:20px;margin:0 0 16px;">Votre abonnement a expiré</h2>
<p style="color:#475569;font-size:15px;line-height:1.6;margin:0 0 12px;">
  Bonjour <strong>{{ $user->first_name ?? $user->name }}</strong>,<br>
  votre abonnement <strong>{{ $subscription->plan->name }}</strong> a expiré le <strong>{{ $subscription->ends_at?->format('d/m/Y') }}</strong>.
</p>
<p style="color:#475569;font-size:14px;line-height:1.6;margin:0 0 20px;">
  Votre accès aux formations est suspendu. Pour continuer à apprendre, renouvelez votre abonnement dès maintenant.
</p>
<table cellpadding="0" cellspacing="0"><tr><td>
  <a href="{{ url('/formules') }}" style="display:inline-block;background:#C9A84C;color:#fff;font-size:14px;font-weight:700;
     padding:12px 28px;border-radius:8px;text-decoration:none;">Renouveler mon abonnement</a>
</td></tr></table>
@endsection
