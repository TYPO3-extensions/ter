<?php
/**
 * Autoloader definition for the Mail component.
 *
 * @package Mail
 * @version 1.1.3
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
return array(
    'ezcMailAddress'                => 'Mail/structs/mail_address.php',
    'ezcMailContentDispositionHeader'=> 'Mail/structs/content_disposition_header.php',
    'ezcMailPart'                   => 'Mail/interfaces/part.php',
    'ezcMailTransport'              => 'Mail/interfaces/transport.php',
    'ezcMail'                       => 'Mail/mail.php',
    'ezcMailComposer'               => 'Mail/composer.php',
    'ezcMailFile'                   => 'Mail/parts/file.php',
    'ezcMailRfc822Digest'           => 'Mail/parts/rfc822_digest.php',
    'ezcMailText'                   => 'Mail/parts/text.php',
    'ezcMailMultipart'              => 'Mail/parts/multipart.php',
    'ezcMailMultipartAlternative'   => 'Mail/parts/multiparts/multipart_alternative.php',
    'ezcMailMultipartMixed'         => 'Mail/parts/multiparts/multipart_mixed.php',
    'ezcMailMultipartRelated'       => 'Mail/parts/multiparts/multipart_related.php',
    'ezcMailMultipartDigest'        => 'Mail/parts/multiparts/multipart_digest.php',
    'ezcMailTransportMta'           => 'Mail/transports/transport_mta.php',
    'ezcMailTransportSmtp'          => 'Mail/transports/transport_smtp.php',
    'ezcMailMtaTransport'           => 'Mail/transports/transport_mta.php',
    'ezcMailSmtpTransport'          => 'Mail/transports/transport_smtp.php',
    'ezcMailTransportConnection'    => 'Mail/transports/transport_connection.php',
    'ezcMailPop3Transport'          => 'Mail/transports/pop3/pop3_transport.php',
    'ezcMailPop3Set'                => 'Mail/transports/pop3/pop3_set.php',
    'ezcMailMboxTransport'          => 'Mail/transports/mbox/mbox_transport.php',
    'ezcMailMboxSet'                => 'Mail/transports/mbox/mbox_set.php',
    'ezcMailFileSet'                => 'Mail/transports/file/file_set.php',
    'ezcMailVariableSet'            => 'Mail/transports/variable/var_set.php',

    'ezcMailException'              => 'Mail/exceptions/mail_exception.php',
    'ezcMailTransportException'     => 'Mail/exceptions/transport_exception.php',
    'ezcMailTransportSmtpException' => 'Mail/exceptions/transport_smtp_exception.php',
    'ezcMailNoSuchMessageException' => 'Mail/exceptions/no_such_message.php',

    'ezcMailTools'                  => 'Mail/tools.php',
    'ezcMailParser'                 => 'Mail/parser/parser.php',
    'ezcMailPartParser'             => 'Mail/parser/interfaces/part_parser.php',
    'ezcMailRfc822Parser'           => 'Mail/parser/parts/rfc822_parser.php',
    'ezcMailTextParser'             => 'Mail/parser/parts/text_parser.php',
    'ezcMailMultipartParser'        => 'Mail/parser/parts/multipart_parser.php',
    'ezcMailMultipartMixedParser'   => 'Mail/parser/parts/multipart_mixed_parser.php',
    'ezcMailMultipartDigestParser'  => 'Mail/parser/parts/multipart_digest_parser.php',
    'ezcMailMultipartAlternativeParser'   => 'Mail/parser/parts/multipart_alternative_parser.php',
    'ezcMailMultipartRelatedParser' => 'Mail/parser/parts/multipart_related_parser.php',
    'ezcMailFileParser'             => 'Mail/parser/parts/file_parser.php',
    'ezcMailRfc822DigestParser'     => 'Mail/parser/parts/rfc822_digest_parser.php',
    'ezcMailHeadersHolder'          => 'Mail/parser/headers_holder.php',
    'ezcMailParserSet'              => 'Mail/parser/interfaces/parser_set.php',
    'ezcMailParserShutdownHandler'  => 'Mail/parser/shutdown_handler.php',
    'ezcMailRfc2231Implementation'  => 'Mail/parser/rfc2231_implementation.php',

    'ezcMailCharsetConverter'       => 'Mail/internal/charset_convert.php',
    'ezcMailHeaderFolder'           => 'Mail/internal/header_folder.php'
    );
?>
