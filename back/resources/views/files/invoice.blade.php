@extends('layouts.file')

@php
  $project = $invoice->project;
  $month = \Carbon\Carbon::parse($project->start_at);
  $to = $month->copy()->lastOfMonth();
  $grossAmount = number_format($invoice->gross_amount, 2, '.', '');
  $vatAmount = number_format($invoice->vat_amount, 2, '.', '');
  $finalAmount = number_format($invoice->final_amount, 2, '.', '');
  $details = json_decode($invoice->details, true);
@endphp

@section('title')
  Facture client - {{ $project->name }} - {{ $month->format('m/Y') }}
@endsection

@section('type')
  Facture client
@endsection

@section('summary')
  <ul>
    <li>Période d'emploi : du <b>{{ $month->format('d/m/Y') }}</b> au <b>{{ $to->format('d/m/Y') }}</b></li>
    <li>Montant à payer (TTC) : <b>{{ $finalAmount }}€</b></li>
    <li><b>Facture n°{{ $invoice->id }}</b></li>
  </ul>
@endsection

@section('header')
  <div class="ui segment">
    <div class="block half">
      <h4 class="header">Employeur</h4>
      <ul class="information">
        <li>Association Numéris ISEP</li>
        <li>28 rue Notre Dame des Champs, 75006 Paris</li>
        <li>SIRET : 822 976 106 00010</li>
      </ul>
    </div>

    <div class="block half">
      <h4 class="header">Client</h4>
      <ul class="information">
        <li>{{ $project->client->name }}</li>
        <li>Projet : {{ $project->name }}</li>
      </ul>
    </div>
  </div>
@endsection

@section('content')
  <table class="ui celled very compact structured table">
    <thead>
    <tr>
      <th>Référence</th>
      <th>Intitué</th>
      <th>Date</th>
      <th>Heures</th>
      <th>Montant</th>
    </tr>
    </thead>

    <tbody>
    @foreach($details as $detail)
      @php
        $reference = $detail['reference'];
        $title = $detail['title'];
        $date = \Carbon\Carbon::parse($detail['startAt'])->format('d/m/Y');
        $bills = $detail['bills'];
      @endphp
      <tr>
        <td class="collapsing">{{ $reference }}</td>
        <td class="collapsing">Tâches administratives</td>
        <td class="collapsing">{{ $date }}</td>
        <td class="collapsing">
          @foreach($bills as $bill)
            <div>{{ $bill['hours'] }} x {{ $bill['rate'] }}</div>
          @endforeach
        </td>
        <td class="collapsing">
          @foreach($bills as $bill)
            <div>{{ $bill['hours'] }} x {{ $bill['amount'] }} = {{ $bill['total'] }}€</div>
          @endforeach
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <div class="block">
    <table class="ui celled very compact structured grey table">
      <tbody>
      <tr>
        <td class="hightlight">Coût de la prestation (HT)</td>
        <td class="center aligned">{{ $grossAmount }}€</td>
      </tr>
      <tr>
        <td class="hightlight">Remboursement des frais (HT)</td>
        <td class="center aligned">0.00€</td>
      </tr>
      <tr>
        <td class="hightlight">TVA (20%)</td>
        <td class="center aligned">{{ $vatAmount }}€</td>
      </tr>
      <tr>
        <td class="hightlight">Montant à payer (TTC)</td>
        <td class="center aligned"><b>{{ $finalAmount }}€</b></td>
      </tr>
      </tbody>
    </table>
  </div>
@endsection

@section('footer')
  <p class="right-aligned text">
    Fait à Paris, le {{ \Carbon\Carbon::parse($invoice->updated_at)->format('d/m/Y') }}.
  </p>

  <div class="ui segment">
    <div class="center-aligned block">
      <div>La présidente, Candice RUMEAU</div>
      <div class="middle-aligned information">
        lu et approuvé
        <img class="ui mini-tall image" src="{{ public_path('images/signature-candice-rumeau.png') }}">
      </div>
    </div>
  </div>
@endsection