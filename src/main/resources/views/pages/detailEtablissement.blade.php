@php
    include(app_path() . '/includes/_gestionBase.inc.php');

    $connexion=connect();
    /*
    if (!$connexion)
    {
        ajouterErreur("Echec de la connexion au serveur MySql");
        afficherErreurs();
        exit();
    }
    */

    // TODO Post (get) on it and remove this mock
    // TODO Gérer cas où on se co sans input id etablissement
    $id = '0350785N';
    // $id = (new Illuminate\Http\Request)->input('id');

// OBTENIR LE DÉTAIL DE L'ÉTABLISSEMENT SÉLECTIONNÉ

    $lgEtab = obtenirDetailEtablissement($connexion, $id);

    $nom = $lgEtab['nom'];
    $adresseRue = $lgEtab['adresseRue'];
    $codePostal = $lgEtab['codePostal'];
    $ville = $lgEtab['ville'];
    $tel = $lgEtab['tel'];
    $adresseElectronique = $lgEtab['adresseElectronique'];
    $type = $lgEtab['type'];
    $civiliteResponsable = $lgEtab['civiliteResponsable'];
    $nomResponsable = $lgEtab['nomResponsable'];
    $prenomResponsable = $lgEtab['prenomResponsable'];
    $nombreChambresOffertes = $lgEtab['nombreChambresOffertes'];

@endphp

<table class='mt-4 table table-bordered table-dark'>
    <thead class='thead-light'>
        <tr>
            <th colspan='2'>{{ $nom }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Id</td>
            <td>{{ $id }}</td>
        </tr>
        <tr>
            <td>Adresse</td>
            <td>{{ $adresseRue }}</td>
        </tr>
        <tr>
            <td>Code postal</td>
            <td>{{ $codePostal }}</td>
        </tr>
        <tr>
            <td>Ville</td>
            <td>{{ $ville }}</td>
        </tr>
        <tr>
            <td>Téléphone</td>
            <td>{{ $tel }}</td>
        </tr>
        <tr>
            <td>E-mail</td>
            <td>{{ $adresseElectronique }}</td>
        </tr>
        <tr>
            <td>Type</td>
            <td>
            @php
                if ($type==1)
                {
                    echo 'Etablissement scolaire';
                }
                else
                {
                    echo 'Autre établissement';
                }
            @endphp
            </td>
        </tr>
        <tr>
            <td>Responsable</td>
            <td>{{ $civiliteResponsable }}&nbsp; {{ $nomResponsable }}&nbsp; {{ $prenomResponsable }}</td>
        </tr>
        <tr>
            <td>Offre</td>
            <td>{{ $nombreChambresOffertes }}&nbsp;chambre(s)</td>
        </tr>
    </tbody>
</table>
<a href='listeEtablissements.php' class='btn btn-outline-info active' >Retour</a>
