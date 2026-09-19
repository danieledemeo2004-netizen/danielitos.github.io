<?php
// invia.php

header('Content-Type: text/plain; charset=utf-8');

// Riceve i dati JSON inviati da JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['nome']) && !empty($data['telefono']) && !empty($data['email']) && !empty($data['auto']) && !empty($data['messaggio'])) {
    
    // Validazione e pulizia dell'email
    $email_utente = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email_utente, FILTER_VALIDATE_EMAIL)) {
        echo "Indirizzo email non valido.";
        exit;
    }

    $nome = htmlspecialchars(trim($data['nome']), ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars(trim($data['telefono']), ENT_QUOTES, 'UTF-8');
    $auto = htmlspecialchars(trim($data['auto']), ENT_QUOTES, 'UTF-8');
    $assicurazione = htmlspecialchars(trim($data['assicurazione']), ENT_QUOTES, 'UTF-8');
    $messaggio = htmlspecialchars(trim($data['messaggio']), ENT_QUOTES, 'UTF-8');
    $foto = !empty($data['foto']) ? $data['foto'] : '';

    // --- CONFIGURAZIONE DESTINATARIO ---
    $a_chi = "info@alicarrozzeria.com"; // La tua email aziendale
    $oggetto = "Nuova richiesta preventivo da parte di " . $nome;
    
    // Costruisce il corpo dell'email
    $corpo  = "Hai ricevuto una nuova richiesta di preventivo dal sito web:\n\n";
    $corpo .= "👤 Nome e Cognome: " . $nome . "\n";
    $corpo .= "📞 Telefono: " . $telefono . "\n";
    $corpo .= "✉️ Email: " . $email_utente . "\n";
    $corpo .= "🚗 Auto: " . $auto . "\n";
    $corpo .= "🛡️ Assicurazione: " . $assicurazione . "\n\n";
    $corpo .= "📝 Messaggio / Danno:\n" . $messaggio . "\n\n";

    if (!empty($foto)) {
        $corpo .= "🖼️ L'utente ha allegato una foto del danno (codificata in Base64).\n";
    }
    
    // Intestazioni (Headers) - Usa un indirizzo del tuo dominio effettivo come mittente
    $headers  = "From: info@alicarrozzeria.com\r\n"; 
    $headers .= "Reply-To: " . $email_utente . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Invio email
    if (mail($a_chi, $oggetto, $corpo, $headers)) {
        echo "Richiesta preventivo inviata con successo!";
    } else {
        echo "Errore durante l'invio dell'email sul server.";
    }

} else {
    echo "Per favore, compila tutti i campi obbligatori.";
}
?>