-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 24 jan. 2025 à 10:22
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bddpartynextdoor`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$5Ub5dA3jb/7Gh6A0GGyDku2I4JdVuXeIVcltxmD8PfNe9Cgr0K.kq');

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_adresse` varchar(255) DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_price` decimal(10,2) NOT NULL,
  `event_tags` text DEFAULT NULL,
  `event_image` varchar(255) NOT NULL,
  `event_description` text DEFAULT NULL,
  `places_available` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `places_reservees` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_adresse`, `event_date`, `event_price`, `event_tags`, `event_image`, `event_description`, `places_available`, `user_id`, `places_reservees`) VALUES
(14, 'party3', 'paris', '2025-01-25', 0.01, 'Concert', 'uploads/6793488fd3951.jpg', 'a', 0, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `faq`
--

INSERT INTO `faq` (`id`, `user_email`, `message`, `created_at`) VALUES
(1, 'camillechaptini@gmail.com', '123\r\n', '2025-01-17 15:49:40'),
(2, 'camillechaptini@gmail.com', '1', '2025-01-17 16:09:40'),
(3, 'camillechaptini@gmail.com', '1', '2025-01-17 16:19:25'),
(4, 'allandu97435@gmail.com', 'AAOLAL\r\n', '2025-01-23 20:49:39'),
(5, 'allandu97435@gmail.com', 'allan', '2025-01-24 09:22:41'),
(6, 'allandu97435@gmail.com', 'all', '2025-01-24 10:21:34');

-- --------------------------------------------------------

--
-- Structure de la table `multiple_content`
--

CREATE TABLE `multiple_content` (
  `page_id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `multiple_content`
--

INSERT INTO `multiple_content` (`page_id`, `page_name`, `content`) VALUES
(1, 'Mentions légales', '<p>&nbsp;</p>\r\n<ol>\r\n<li>\r\n<p><strong>&Eacute;diteur du site</strong></p>\r\n<ul>\r\n<li><strong>Nom de la soci&eacute;t&eacute;</strong> : PartyNextDoor SAS</li>\r\n<li><strong>Si&egrave;ge social</strong> : 28 Rue Notre Dame des Champs, 75006 Paris</li>\r\n<li><strong>Num&eacute;ro SIRET</strong> : 123 456 789 00012</li>\r\n<li><strong>Contact</strong> : <a href=\"mailto:6newdev@gmail.com\">6newdev@gmail.com</a> | +33 (0)1 23 45 67 89</li>\r\n</ul>\r\n<p><strong>Acc&egrave;s au Site</strong><br />Le Site est accessible gratuitement &agrave; tout utilisateur disposant d&rsquo;une connexion Internet. Les frais li&eacute;s &agrave; l&rsquo;acc&egrave;s (mat&eacute;riel, logiciels, abonnement Internet) sont &agrave; la charge de l&rsquo;utilisateur.</p>\r\n<p><strong>Services propos&eacute;s</strong><br />Le Site permet notamment :</p>\r\n<ul>\r\n<li>La consultation et l&rsquo;achat de billets pour des &eacute;v&eacute;nements.</li>\r\n<li>La gestion d&rsquo;&eacute;v&eacute;nements pour les organisateurs.</li>\r\n<li>L&rsquo;acc&egrave;s &agrave; des informations sur des soir&eacute;es, concerts et autres activit&eacute;s.</li>\r\n</ul>\r\n<p><strong>Obligations de l&rsquo;utilisateur</strong><br />L&rsquo;utilisateur s&rsquo;engage &agrave; :</p>\r\n<ul>\r\n<li>Utiliser le Site conform&eacute;ment &agrave; la l&eacute;gislation en vigueur et aux pr&eacute;sentes CGU.</li>\r\n<li>Ne pas utiliser le Site pour des activit&eacute;s frauduleuses ou ill&eacute;gales.</li>\r\n<li>Fournir des informations exactes lors de son inscription ou de ses transactions.</li>\r\n</ul>\r\n<p><strong>Responsabilit&eacute;</strong><br />PartyNextDoor SAS met en &oelig;uvre tous les moyens pour assurer le bon fonctionnement du Site, mais ne peut &ecirc;tre tenue responsable :</p>\r\n<ul>\r\n<li>D&rsquo;&eacute;ventuelles interruptions du service (maintenance, panne technique).</li>\r\n<li>Des contenus publi&eacute;s par des tiers, y compris les organisateurs d&rsquo;&eacute;v&eacute;nements.</li>\r\n</ul>\r\n<p><strong>Propri&eacute;t&eacute; intellectuelle</strong><br />Tous les &eacute;l&eacute;ments du Site (textes, graphismes, marques, etc.) sont la propri&eacute;t&eacute; exclusive de PartyNextDoor SAS ou de ses partenaires. Toute reproduction ou exploitation est strictement interdite sans autorisation.</p>\r\n<p><strong>Donn&eacute;es personnelles</strong><br />Les donn&eacute;es personnelles collect&eacute;es sur le Site sont trait&eacute;es conform&eacute;ment &agrave; notre Politique de confidentialit&eacute;.</p>\r\n<p><strong>Modifications des CGU</strong><br />PartyNextDoor SAS se r&eacute;serve le droit de modifier les pr&eacute;sentes CGU &agrave; tout moment. Les utilisateurs seront inform&eacute;s des changements via une notification sur le Site.</p>\r\n<p><strong>Loi applicable et juridiction</strong><br />Les pr&eacute;sentes CGU sont r&eacute;gies par le droit fran&ccedil;ais. En cas de litige, les tribunaux comp&eacute;tents sont ceux de Paris.</p>\r\n</li>\r\n</ol>'),
(2, 'Cookies', '<p><strong>Cookies</strong><br />PartyNextDoor SAS (\"nous\", \"notre\" ou \"nos\") s\'engage &agrave; prot&eacute;ger vos donn&eacute;es personnelles conform&eacute;ment aux lois en vigueur, notamment le R&egrave;glement G&eacute;n&eacute;ral sur la Protection des Donn&eacute;es (RGPD).</p>\r\n<h3>1. Donn&eacute;es collect&eacute;es</h3>\r\n<ul>\r\n<li><strong>Donn&eacute;es fournies directement</strong> : Informations telles que nom, pr&eacute;nom, email, num&eacute;ro de t&eacute;l&eacute;phone lors de l\'inscription, l\'achat de billets ou la cr&eacute;ation d\'&eacute;v&eacute;nements.</li>\r\n<li><strong>Donn&eacute;es techniques</strong> : Informations collect&eacute;es automatiquement via des cookies (adresse IP, type de navigateur, pages visit&eacute;es).</li>\r\n<li><strong>Donn&eacute;es de transaction</strong> : D&eacute;tails li&eacute;s &agrave; vos achats ou paiements (hors informations de carte bancaire).</li>\r\n</ul>\r\n<h3>2. Utilisation des donn&eacute;es</h3>\r\n<p>Vos donn&eacute;es sont utilis&eacute;es pour :</p>\r\n<ul>\r\n<li>Assurer le bon fonctionnement des services.</li>\r\n<li>G&eacute;rer commandes et paiements.</li>\r\n<li>Envoyer des notifications concernant &eacute;v&eacute;nements et services.</li>\r\n<li>Personnaliser votre exp&eacute;rience utilisateur.</li>\r\n<li>Respecter les obligations l&eacute;gales.</li>\r\n</ul>\r\n<h3>3. Partage des donn&eacute;es</h3>\r\n<p>Vos donn&eacute;es peuvent &ecirc;tre partag&eacute;es avec :</p>\r\n<ul>\r\n<li>Nos partenaires (organisateurs d\'&eacute;v&eacute;nements, prestataires de paiement).</li>\r\n<li>Les autorit&eacute;s comp&eacute;tentes en cas d\'obligation l&eacute;gale.</li>\r\n</ul>\r\n<h3>4. Dur&eacute;e de conservation</h3>\r\n<p>Vos donn&eacute;es sont conserv&eacute;es aussi longtemps que n&eacute;cessaire pour atteindre les finalit&eacute;s d&eacute;crites ou conform&eacute;ment &agrave; la loi :</p>\r\n<ul>\r\n<li><strong>Donn&eacute;es de compte</strong> : Jusqu\'&agrave; la suppression du compte.</li>\r\n<li><strong>Donn&eacute;es de transaction</strong> : Selon les obligations fiscales (5 &agrave; 10 ans).</li>\r\n</ul>\r\n<h3>5. Vos droits</h3>\r\n<p>Conform&eacute;ment au RGPD, vous avez le droit :</p>\r\n<ul>\r\n<li>D\'acc&egrave;s : Consulter vos donn&eacute;es.</li>\r\n<li>De rectification : Corriger des donn&eacute;es inexactes.</li>\r\n<li>&Agrave; l\'effacement : Demander la suppression de vos donn&eacute;es.</li>\r\n<li>D\'opposition : Refuser certains traitements.</li>\r\n<li>&Agrave; la portabilit&eacute; : R&eacute;cup&eacute;rer vos donn&eacute;es dans un format structur&eacute;.</li>\r\n</ul>\r\n<p>Pour exercer ces droits, contactez-nous &agrave; : <strong><a href=\"mailto:6newdev@gmail.com\" rel=\"noopener\">6newdev@gmail.com</a></strong></p>\r\n<h3>6. Cookies</h3>\r\n<p>Nous utilisons des cookies pour am&eacute;liorer votre exp&eacute;rience. Vous pouvez configurer vos pr&eacute;f&eacute;rences via votre navigateur ou notre outil de gestion des cookies.</p>\r\n<h3>7. S&eacute;curit&eacute; des donn&eacute;es</h3>\r\n<p>Des mesures techniques et organisationnelles prot&egrave;gent vos donn&eacute;es contre acc&egrave;s non autoris&eacute;, perte ou divulgation.</p>\r\n<h3>8. Modifications</h3>\r\n<p>Nous nous r&eacute;servons le droit de modifier cette politique. Les modifications seront publi&eacute;es sur cette page avec une date de mise &agrave; jour.</p>\r\n<h3>9. Contact</h3>\r\n<p>Pour toute question sur notre Politique de confidentialit&eacute; : <strong><a href=\"mailto:6newdev@gmail.com\" rel=\"noopener\">6newdev@gmail.com</a></strong></p>'),
(3, 'Politique de confidentialité', '<p><strong>Politique de confidentialit&eacute;</strong></p>\r\n<p><strong>1. Donn&eacute;es collect&eacute;es</strong></p>\r\n<ul>\r\n<li><strong>Donn&eacute;es fournies directement</strong> : Informations comme le nom, pr&eacute;nom, email, et num&eacute;ro de t&eacute;l&eacute;phone, recueillies lors de l\'inscription, de l\'achat de billets ou de la cr&eacute;ation d\'&eacute;v&eacute;nements.</li>\r\n<li><strong>Donn&eacute;es techniques</strong> : Informations collect&eacute;es automatiquement via des cookies (adresse IP, type de navigateur, pages visit&eacute;es, etc.).</li>\r\n<li><strong>Donn&eacute;es de transaction</strong> : D&eacute;tails li&eacute;s &agrave; vos achats ou paiements (hors informations de carte bancaire).</li>\r\n</ul>\r\n<p><strong>2. Utilisation des donn&eacute;es</strong><br />Vos donn&eacute;es sont utilis&eacute;es pour :</p>\r\n<ul>\r\n<li>Assurer le bon fonctionnement des services.</li>\r\n<li>G&eacute;rer commandes et paiements.</li>\r\n<li>Envoyer des notifications concernant &eacute;v&eacute;nements et services.</li>\r\n<li>Personnaliser votre exp&eacute;rience utilisateur.</li>\r\n<li>Respecter nos obligations l&eacute;gales.</li>\r\n</ul>\r\n<p><strong>3. Partage des donn&eacute;es</strong><br />Vos donn&eacute;es peuvent &ecirc;tre partag&eacute;es avec :</p>\r\n<ul>\r\n<li>Nos partenaires (organisateurs d\'&eacute;v&eacute;nements, prestataires de paiement).</li>\r\n<li>Les autorit&eacute;s comp&eacute;tentes en cas d\'obligation l&eacute;gale.</li>\r\n</ul>\r\n<p><strong>4. Dur&eacute;e de conservation</strong><br />Vos donn&eacute;es sont conserv&eacute;es aussi longtemps que n&eacute;cessaire pour atteindre les finalit&eacute;s d&eacute;crites ou conform&eacute;ment &agrave; la loi :</p>\r\n<ul>\r\n<li><strong>Donn&eacute;es de compte</strong> : Jusqu\'&agrave; la suppression du compte.</li>\r\n<li><strong>Donn&eacute;es de transaction</strong> : Selon les obligations fiscales (5 &agrave; 10 ans).</li>\r\n</ul>\r\n<p><strong>5. Vos droits</strong><br />Conform&eacute;ment au RGPD, vous disposez des droits suivants :</p>\r\n<ul>\r\n<li><strong>Droit d\'acc&egrave;s</strong> : Consulter vos donn&eacute;es.</li>\r\n<li><strong>Droit de rectification</strong> : Corriger des donn&eacute;es inexactes.</li>\r\n<li><strong>Droit &agrave; l\'effacement</strong> : Demander la suppression de vos donn&eacute;es.</li>\r\n<li><strong>Droit d\'opposition</strong> : Refuser certains traitements.</li>\r\n<li><strong>Droit &agrave; la portabilit&eacute;</strong> : R&eacute;cup&eacute;rer vos donn&eacute;es dans un format structur&eacute;.<br />Pour exercer vos droits, contactez-nous &agrave; : <strong><a href=\"mailto:6newdev@gmail.com\">6newdev@gmail.com</a></strong></li>\r\n</ul>\r\n<p><strong>6. Cookies</strong><br />Nous utilisons des cookies pour am&eacute;liorer votre exp&eacute;rience. Vous pouvez configurer vos pr&eacute;f&eacute;rences via votre navigateur ou notre outil de gestion des cookies.</p>\r\n<p><strong>7. S&eacute;curit&eacute; des donn&eacute;es</strong><br />Nous appliquons des mesures techniques et organisationnelles pour prot&eacute;ger vos donn&eacute;es contre acc&egrave;s non autoris&eacute;, perte ou divulgation.</p>\r\n<p><strong>8. Modifications</strong><br />Cette politique peut &ecirc;tre modifi&eacute;e. Les changements seront publi&eacute;s sur cette page avec une date de mise &agrave; jour.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>9. Contact</strong><br />Pour toute question sur notre Politique de confidentialit&eacute;, contactez-nous &agrave; : <strong><a href=\"mailto:6newdev@gmail.com\">6newdev@gmail.com</a></strong></p>'),
(4, 'Conditions d\'utilisation', '<p><strong>1. Objet&nbsp;</strong>&nbsp;</p>\r\n<p>Les pr&eacute;sentes Conditions G&eacute;n&eacute;rales d\'Utilisation (CGU) r&eacute;gissent l\'acc&egrave;s et l\'utilisation de la plateforme PartyNextDoor, incluant le site web et les applications associ&eacute;es. En utilisant nos services, vous acceptez sans r&eacute;serve ces CGU.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>2. Services propos&eacute;s</strong>&nbsp;&nbsp;</p>\r\n<p>PartyNextDoor est une plateforme d&eacute;di&eacute;e &agrave; la d&eacute;couverte, &agrave; la gestion et &agrave; la r&eacute;servation d\'&eacute;v&eacute;nements, concerts, festivals et soir&eacute;es. Nous agissons en tant qu\'interm&eacute;diaire entre les organisateurs d\'&eacute;v&eacute;nements et les utilisateurs.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>3. Acc&egrave;s &agrave; la plateforme&nbsp;&nbsp;</strong></p>\r\n<ul>\r\n<li>L\'acc&egrave;s est r&eacute;serv&eacute; aux utilisateurs &acirc;g&eacute;s d\'au moins 18 ans ou ayant l\'autorisation d\'un repr&eacute;sentant l&eacute;gal.&nbsp;&nbsp;</li>\r\n<li>Vous devez disposer d\'un &eacute;quipement personnel (ordinateur, smartphone) et d\'une connexion internet pour acc&eacute;der &agrave; nos services.&nbsp;&nbsp;</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p><strong>&nbsp;4. Cr&eacute;ation de compte</strong>&nbsp;&nbsp;</p>\r\n<ul>\r\n<li>Un compte est obligatoire pour acc&eacute;der &agrave; certains services (achat de billets, gestion de vos &eacute;v&eacute;nements).&nbsp;&nbsp;</li>\r\n<li>Vous devez fournir des informations exactes, compl&egrave;tes et &agrave; jour.&nbsp;&nbsp;</li>\r\n<li>La confidentialit&eacute; des identifiants de connexion est sous votre responsabilit&eacute;.</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p><strong>5. Engagements des utilisateurs</strong>&nbsp;&nbsp;</p>\r\n<p>En utilisant la plateforme, vous vous engagez &agrave; :&nbsp;&nbsp;</p>\r\n<ul>\r\n<li>Respecter les lois en vigueur.&nbsp;&nbsp;</li>\r\n<li>Ne pas diffuser de contenus illicites, offensants ou diffamatoires.&nbsp;&nbsp;</li>\r\n<li>Ne pas utiliser nos services pour des activit&eacute;s frauduleuses ou interdites.</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p><strong>6. Propri&eacute;t&eacute; intellectuelle&nbsp;&nbsp;</strong></p>\r\n<ul>\r\n<li>Tous les contenus (textes, images, logos, vid&eacute;os) sont la propri&eacute;t&eacute; exclusive de PartyNextDoor ou de ses partenaires.&nbsp;&nbsp;</li>\r\n<li>Toute reproduction, modification ou utilisation non autoris&eacute;e est interdite.</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p><strong>7. Billetterie&nbsp;&nbsp;</strong></p>\r\n<ul>\r\n<li>Les billets achet&eacute;s sur la plateforme sont soumis aux conditions des organisateurs.&nbsp;&nbsp;</li>\r\n<li>Aucun remboursement ne sera effectu&eacute;, sauf disposition sp&eacute;cifique de l\'&eacute;v&eacute;nement ou en cas d\'annulation.</li>\r\n</ul>\r\n<p><strong>8. Responsabilit&eacute;s&nbsp;&nbsp;</strong></p>\r\n<ul>\r\n<li>PartyNextDoor d&eacute;cline toute responsabilit&eacute; en cas de :&nbsp;&nbsp;</li>\r\n<li>Annulation ou modification d&rsquo;un &eacute;v&eacute;nement par l&rsquo;organisateur.&nbsp;&nbsp;</li>\r\n<li>Probl&egrave;mes techniques ou interruption temporaire des services.&nbsp;&nbsp;</li>\r\n<li>Nous faisons notre maximum pour garantir la fiabilit&eacute; et la s&eacute;curit&eacute; de notre plateforme.</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p><strong>9. Donn&eacute;es personnelles</strong>&nbsp;&nbsp;</p>\r\n<ul>\r\n<li>Vos donn&eacute;es sont collect&eacute;es et trait&eacute;es conform&eacute;ment &agrave; notre Politique de Confidentialit&eacute;.&nbsp;&nbsp;</li>\r\n<li>Vous disposez d&rsquo;un droit d&rsquo;acc&egrave;s, de rectification et de suppression de vos donn&eacute;es.</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p><strong>10. R&eacute;siliation</strong>&nbsp;&nbsp;</p>\r\n<p>PartyNextDoor se r&eacute;serve le droit de suspendre ou de supprimer un compte utilisateur en cas de violation des CGU ou d\'utilisation abusive de nos services.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>11. Modification des CGU&nbsp;&nbsp;</strong></p>\r\n<p>Ces CGU peuvent &ecirc;tre modifi&eacute;es &agrave; tout moment. Les utilisateurs seront inform&eacute;s des changements, et la poursuite de l&rsquo;utilisation de nos services vaut acceptation des nouvelles conditions.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>12. Loi applicable et juridiction comp&eacute;tente&nbsp;&nbsp;</strong></p>\r\n<p>Les pr&eacute;sentes CGU sont r&eacute;gies par les lois fran&ccedil;aises. Tout litige sera soumis &agrave; la comp&eacute;tence exclusive des tribunaux de Paris.</p>\r\n<p>&nbsp;</p>');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `numero_carte` varchar(16) NOT NULL,
  `date_fin_validite` varchar(5) NOT NULL,
  `cryptogramme_visuel` varchar(3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `revente_tickets`
--

CREATE TABLE `revente_tickets` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('en_vente','vendu') DEFAULT 'en_vente',
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `event_id`, `price`, `created_at`) VALUES
(67, 4, 14, 0.01, '2025-01-24 08:01:25');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom_utilisateur` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `nom_de_famille` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `photo_profil` varchar(255) DEFAULT 'default.jpg',
  `token` varchar(255) DEFAULT NULL,
  `token_expiration` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom_utilisateur`, `email`, `mot_de_passe`, `nom_de_famille`, `prenom`, `bio`, `photo_profil`, `token`, `token_expiration`, `last_activity`) VALUES
(4, 'all', 'allandu97435@gmail.com', '$2y$10$vOb/dBKCQblPe5Asuw2rJu07Dg8EWo6Hf1EAEO4nV2aKP3aNYulv2', 'AA', 'AA', 'AA', 'uploads/mam.jpg', 'e35dab43cb69a8058a4fea37d73dc44159bc498b06e0eb5e2fc0cfee4491db8ec4c64bbbf3e23856911b51eed9e9dde6c97b', '2025-01-25 09:25:50', '2025-01-24 09:25:50'),
(6, 'Camille', 'camillechaptini@gmail.com', '$2y$10$NmJ1WBXWAT2bUU0jHmXXheglvah/ID2mzCAHeeoaCJj1b8qrvmutO', 'Chaptini', 'Camille', '123', 'uploads/cold2.jpg', NULL, NULL, '2025-01-17 14:49:38'),
(8, 'alllan', 'benard@gmail.com', '$2y$10$sXuYM.LkhMBdZiDs4evV1ucZdMWSnFdA0AAs1i0.z11TCQiXT7tZq', NULL, NULL, NULL, 'default.jpg', NULL, NULL, '2025-01-24 00:24:17'),
(9, 'al', 'all@gmail.com', '$2y$10$1dK4X6qSnRAPfzZXBvIbrO4NsGAVVFLxThGrHXvGtM4JnTzsrSvUy', NULL, NULL, NULL, 'default.jpg', NULL, NULL, '2025-01-24 09:31:10');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id_event` (`user_id`);

--
-- Index pour la table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `multiple_content`
--
ALTER TABLE `multiple_content`
  ADD PRIMARY KEY (`page_id`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Index pour la table `revente_tickets`
--
ALTER TABLE `revente_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id_ticket` (`user_id`),
  ADD KEY `fk_event_id_ticket` (`event_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `multiple_content`
--
ALTER TABLE `multiple_content`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `revente_tickets`
--
ALTER TABLE `revente_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_user_id_event` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `paiements_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Contraintes pour la table `revente_tickets`
--
ALTER TABLE `revente_tickets`
  ADD CONSTRAINT `revente_tickets_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);

--
-- Contraintes pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_event_id_ticket` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id_ticket` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
