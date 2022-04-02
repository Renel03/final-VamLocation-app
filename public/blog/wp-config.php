<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'blog_allocar_mg' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'root' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost:3308' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'k6=A2h/HwjOC)uF^P5.={F@jBf!!5R&`7h/K!xPX+NJc&+cs*oNE+I*3&,?LdnDc' );
define( 'SECURE_AUTH_KEY',  '`v+LgmY%iQZt/@?>nL|83cbV*)BNRD?z5(fcUQ*!*2Dz?Xz4e4F+:tuDV(B*wnBY' );
define( 'LOGGED_IN_KEY',    'K.)vV-Sa2sY[*RS.huPb*sc,sXl(=_)*98B+`:Ayv-!>h$xpVQztpvsPn-=*f8CX' );
define( 'NONCE_KEY',        ' jk9Z>Yy;V7D$.K:d[|y=QxUG+8Ae3?|`FiCI:MV!O_j3(M%1MzPnqV(W>)]}GEC' );
define( 'AUTH_SALT',        '.GkkIwuwtt7baO=fRTwdg@d;5[mR%V}y:7l4VhhCW2q/|7U@h7%%J*X?,Vs:eR%d' );
define( 'SECURE_AUTH_SALT', 'R<R*LbF5>td5}4MDFfa~^=edz}^F>-Xh_TlGA,)~k*YU1Q /nO:sbf& }x||!B-v' );
define( 'LOGGED_IN_SALT',   '7z4~Jl8Qdb<|Iv`mfQb8+H.f>r5LneVfk5bXw$6xPO_p9{r=W8%M;z=+T&w_-bi<' );
define( 'NONCE_SALT',       '=yWq/pw)&E}2b16P|{$VKl=RBD-o C3G)~3_PgS+Mx?^2H]8V?)g#;v8Ifixl~ve' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
