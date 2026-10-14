@extends('emails.layout')
@section('content')
<h2 style="color:#0B1E3E;font-size:20px;margin:0 0 16px;">Demande d'abonnement refusée</h2>
<p style="color:#475569;font-size:15px;line-height:1.6;margin:0 0 12px;">
  Bonjour <strong>{{ $user->first_name ?? $user->name }}</strong>,<br>
  nous sommes au regret de vous informer que votre demande d'abonnement n'a pas pu être validée.
</p>
<table cellpadding="0" cellspacing="0" style="background:#fff7f7;border:1px solid #fecaca;border-radius:8px;padding:16px 20px;margin:20px 0;width:100%;">
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;width:140px;">Formule</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->plan->name }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Statut</td>
      <td><span style="background:#fee2e2;color:#991b1b;font-size:12px;font-weight:700;padding:2px 10px;border-radius:20px;">Annulé</span></td></tr>
</table>
<p style="color:#475569;font-size:14px;line-height:1.6;">
  Si vous pensez qu'il s'agit d'une erreur ou souhaitez soumettre une nouvelle demande, n'hésitez pas à nous contacter ou à réessayer depuis votre espace.
</p>
<table cellpadding="0" cellspacing="0"><tr><td>
  <a href="{{ url('/formules') }}" style="display:inline-block;background:#1B4FD8;color:#fff;font-size:14px;font-weight:700;
     padding:12px 28px;border-radius:8px;text-decoration:none;">Voir les formules</a>
</td></tr></table>
@endsection
