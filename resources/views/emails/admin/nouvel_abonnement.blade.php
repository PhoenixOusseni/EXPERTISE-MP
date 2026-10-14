@extends('emails.layout')
@section('content')
<h2 style="color:#0B1E3E;font-size:20px;margin:0 0 16px;">Nouvelle demande d'abonnement</h2>
<p style="color:#475569;font-size:15px;line-height:1.6;margin:0 0 16px;">
  Une nouvelle demande d'abonnement est en attente de validation.
</p>
<table cellpadding="0" cellspacing="0" style="background:#f8faff;border:1px solid #dbeafe;border-radius:8px;padding:16px 20px;margin:0 0 20px;width:100%;">
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;width:150px;">Apprenant</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->user->name }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Email</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->user->email }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Téléphone</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->user->phone ?? '—' }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Formule</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->plan->name }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Montant</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">
        {{ number_format($subscription->amount, 0, '.', ' ') }} {{ $subscription->currency }}
        @if($subscription->plan->price_xof)
          / {{ number_format($subscription->plan->price_xof, 0, '.', ' ') }} XOF
        @endif
      </td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Mode de paiement</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->payment_method }}</td></tr>
  @if($subscription->payment_reference)
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Référence</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->payment_reference }}</td></tr>
  @endif
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Date</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $subscription->created_at->format('d/m/Y H:i') }}</td></tr>
</table>
<table cellpadding="0" cellspacing="0"><tr><td>
  <a href="{{ url('/admin/subscriptions/' . $subscription->id) }}" style="display:inline-block;background:#1B4FD8;color:#fff;font-size:14px;font-weight:700;
     padding:12px 28px;border-radius:8px;text-decoration:none;">Valider / Refuser</a>
</td></tr></table>
@endsection
