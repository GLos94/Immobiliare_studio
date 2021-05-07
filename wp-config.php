<?php
/**
 * Il file base di configurazione di WordPress.
 *
 * Questo file viene utilizzato, durante l’installazione, dallo script
 * di creazione di wp-config.php. Non è necessario utilizzarlo solo via web
 * puoi copiare questo file in «wp-config.php» e riempire i valori corretti.
 *
 * Questo file definisce le seguenti configurazioni:
 *
 * * Impostazioni MySQL
 * * Chiavi Segrete
 * * Prefisso Tabella
 * * ABSPATH
 *
 * * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Impostazioni MySQL - È possibile ottenere queste informazioni dal proprio fornitore di hosting ** //
/** Il nome del database di WordPress */
define( 'DB_NAME', 'studiocasa' );

/** Nome utente del database MySQL */
define( 'DB_USER', 'root' );

/** Password del database MySQL */
define( 'DB_PASSWORD', 'root' );

/** Hostname MySQL  */
define( 'DB_HOST', 'localhost' );

/** Charset del Database da utilizzare nella creazione delle tabelle. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Il tipo di Collazione del Database. Da non modificare se non si ha idea di cosa sia. */
define('DB_COLLATE', '');



/**#@+
 * Chiavi Univoche di Autenticazione e di Salatura.
 *
 * Modificarle con frasi univoche differenti!
 * È possibile generare tali chiavi utilizzando {@link https://api.wordpress.org/secret-key/1.1/salt/ servizio di chiavi-segrete di WordPress.org}
 * È possibile cambiare queste chiavi in qualsiasi momento, per invalidare tuttii cookie esistenti. Ciò forzerà tutti gli utenti ad effettuare nuovamente il login.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '|UcnzM`7Xes8pL,;ja^OTwq+1_bd3#~yJ^1/,qB(Mp4y`h;EKrSgxmU^Dzp(<B@-' );
define( 'SECURE_AUTH_KEY',  'U3>jNFi;{KwzG ADBp{ol D7Rc!8!qL[juO=/N*Cc[bvMAAiLj6l>v4Uo[V7F//c' );
define( 'LOGGED_IN_KEY',    'A|v%vHU(=e*Dl-N~kF(n[i2$^G$kZFkO,2HXNFB_#.MS/80h[c6dcF[lXU,`Ac:~' );
define( 'NONCE_KEY',        '! ],mm0[1XFLa2EF!a;SbYC|4~0hnh$=j{qib]#@u~:_xk`t>:V.@]7lS&iQ`6J?' );
define( 'AUTH_SALT',        '-M=p^E!_xk.7LIifejEPhCKK8DB5ULohYQ,>aCL~^YR|fE#cSOyVkRuSR$jEF<I(' );
define( 'SECURE_AUTH_SALT', 'Op9a_#|S`!1Y57QH!E^/$WU4Uw+]PLEO?Skt86hj2b(0>td~iq5o&wwJ/3{a:=N~' );
define( 'LOGGED_IN_SALT',   '6fVKvzW=9Qss3g9Al9$84Z6zI[|9v#x.tq)ejqbQc}=eC+Y7K)?,GI6fhj3{:cCD' );
define( 'NONCE_SALT',       'B&C5o;aQfZm5sen(4pNAw,iqU3J#GDdRs1N)5mWBNOcFQ(eT^0(SMnpIPd@uEIcz' );

/**#@-*/

/**
 * Prefisso Tabella del Database WordPress.
 *
 * È possibile avere installazioni multiple su di un unico database
 * fornendo a ciascuna installazione un prefisso univoco.
 * Solo numeri, lettere e sottolineatura!
 */
$table_prefix = 'wp_';

/**
 * Per gli sviluppatori: modalità di debug di WordPress.
 *
 * Modificare questa voce a TRUE per abilitare la visualizzazione degli avvisi durante lo sviluppo
 * È fortemente raccomandato agli svilupaptori di temi e plugin di utilizare
 * WP_DEBUG all’interno dei loro ambienti di sviluppo.
 *
 * Per informazioni sulle altre costanti che possono essere utilizzate per il debug,
 * leggi la documentazione
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);
define( 'JETPACK_DEV_DEBUG', true);

/* Finito, interrompere le modifiche! Buon blogging. */

/** Path assoluto alla directory di WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Imposta le variabili di WordPress ed include i file. */
require_once(ABSPATH . 'wp-settings.php');
