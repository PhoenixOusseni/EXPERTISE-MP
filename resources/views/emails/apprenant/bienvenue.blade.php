@extends('emails.layout')
@section('content')
<h2 style="color:#0B1E3E;font-size:20px;margin:0 0 16px;">Bienvenue, {{ $user->first_name ?? $user->name }} ! 🎉</h2>
<p style="color:#475569;font-size:15px;line-height:1.6;margin:0 0 12px;">
  Votre compte a bien été créé sur <strong>E-Formation</strong>. Vous pouvez dès maintenant vous connecter et accéder à votre espace de formation.
</p>
<table cellpadding="0" cellspacing="0" style="background:#f8faff;border:1px solid #dbeafe;border-radius:8px;padding:16px 20px;margin:20px 0;width:100%;">
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;width:130px;">Profil</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->profile_type?->label() ?? '—' }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Email</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->email }}</td></tr>
  @if($user->intitule)
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Intitulé</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->intitule }}</td></tr>
  @endif
</table>
<p style="color:#475569;font-size:14px;line-height:1.6;margin:0 0 24px;">
  Pour profiter de toutes les vidéos de formation, choisissez une formule d'abonnement adaptée à votre profil.
</p>
<table cellpadding="0" cellspacing="0"><tr><td>
  <a href="{{ url('/formules') }}" style="display:inline-block;background:#1B4FD8;color:#fff;font-size:14px;font-weight:700;
     padding:12px 28px;border-radius:8px;text-decoration:none;">Voir les formules</a>
</td></tr></table>
@endsection
