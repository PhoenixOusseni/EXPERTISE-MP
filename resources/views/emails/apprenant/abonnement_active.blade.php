@extends('emails.layout')
@section('content')
<h2 style="color:#0B1E3E;font-size:20px;margin:0 0 16px;">✅ Abonnement activé !</h2>
<p style="color:#475569;font-size:15px;line-height:1.6;margin:0 0 12px;">
  Bonjour <strong>{{ $user->first_name ?? $user->name }}</strong>,<br>
  votre abonnement a été validé et est désormais actif. Accédez dès maintenant à toutes vos formations.
</p>
<table cellpadding="0" cellspacing="0" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px 20px;margin:20px 0;width:100%;">
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;width:140px;">Formule</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->plan->name }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Début</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->starts_at?->format('d/m/Y') }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Expiration</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->ends_at?->format('d/m/Y') }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Statut</td>
      <td><span style="background:#dcfce7;color:#166534;font-size:12px;font-weight:700;padding:2px 10px;border-radius:20px;">Actif</span></td></tr>
</table>
<table cellpadding="0" cellspacing="0"><tr><td>
  <a href="{{ url('/catalogue') }}" style="display:inline-block;background:#059669;color:#fff;font-size:14px;font-weight:700;
     padding:12px 28px;border-radius:8px;text-decoration:none;">Accéder aux formations</a>
</td></tr></table>
@endsection
