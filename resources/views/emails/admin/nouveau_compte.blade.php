@extends('emails.layout')
@section('content')
<h2 style="color:#0B1E3E;font-size:20px;margin:0 0 16px;">Nouveau compte apprenant</h2>
<p style="color:#475569;font-size:15px;line-height:1.6;margin:0 0 16px;">
  Un nouvel apprenant vient de s'inscrire sur E-Formation.
</p>
<table cellpadding="0" cellspacing="0" style="background:#f8faff;border:1px solid #dbeafe;border-radius:8px;padding:16px 20px;margin:0 0 20px;width:100%;">
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;width:130px;">Nom complet</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->name }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Email</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->email }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Téléphone</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->phone ?? '—' }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Profil</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->profile_type?->label() ?? '—' }}</td></tr>
  @if($user->intitule)
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Intitulé</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->intitule }}</td></tr>
  @endif
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Pays / Ville</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->country }} / {{ $user->city }}</td></tr>
  <tr><td style="color:#64748b;font-size:13px;padding:3px 0;">Inscrit le</td>
      <td style="color:#0B1E3E;font-weight:600;font-size:13px;">{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
</table>
<table cellpadding="0" cellspacing="0"><tr><td>
  <a href="{{ url('/admin/users/' . $user->id) }}" style="display:inline-block;background:#1B4FD8;color:#fff;font-size:14px;font-weight:700;
     padding:12px 28px;border-radius:8px;text-decoration:none;">Voir le profil</a>
</td></tr></table>
@endsection
