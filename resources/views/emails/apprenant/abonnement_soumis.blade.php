@extends('emails.layout')
@section('content')
<h2 style="color:#0B1E3E;font-size:20px;margin:0 0 16px;">Demande d'abonnement reçue</h2>
<p style="color:#475569;font-size:15px;line-height:1.6;margin:0 0 12px;">
  Bonjour <strong>{{ $user->first_name ?? $user->name }}</strong>,<br>
  votre demande d'abonnement a bien été enregistrée. Elle sera activée dès vérification de votre paiement.
</p>
<table cellpadding="0" cellspacing="0" style="background:#f8faff;border:1px solid #dbeafe;border-radius:8px;padding:16px 20px;margin:20px 0;width:100%;">
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;width:140px;">Formule</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->plan->name }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Durée</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->plan->duration_days }} jours</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Montant</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">
        {{ number_format($subscription->amount, 0, '.', ' ') }} {{ $subscription->currency }}
        @if($subscription->plan->price_xof)
          <span style="color:#94a3b8;font-weight:400;"> / {{ number_format($subscription->plan->price_xof, 0, '.', ' ') }} XOF</span>
        @endif
      </td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Mode de paiement</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->payment_method }}</td></tr>
  @if($subscription->payment_reference)
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Référence</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->payment_reference }}</td></tr>
  @endif
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Statut</td>
      <td><span style="background:#fef3c7;color:#92400e;font-size:12px;font-weight:700;padding:2px 10px;border-radius:20px;">En attente</span></td></tr>
</table>
<p style="color:#64748b;font-size:13px;">Vous serez notifié par e-mail dès que votre abonnement sera activé.</p>
@endsection
