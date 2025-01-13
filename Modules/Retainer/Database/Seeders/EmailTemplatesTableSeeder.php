<?php

namespace Modules\Retainer\Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class EmailTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $emailTemplate = [
            'Retainer Send',
            'Retainer Payment Create',
        ];
        $defaultTemplate = [
            'Retainer Send' => [
                'subject' => 'Retainer Send',
                'variables' => '{
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "Retainer Name": "retainer_name",
                    "Retainer Number": "retainer_number",
                    "retainer_url": "retainer_url"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبا ، { retainer_name }</p>
                            <p>مرحبا بك في { app_name }</p>
                            <p>أتمنى أن يجدك هذا البريد الإلكتروني جيدا ! ! برجاء الرجوع الى رقم أداة الاحتجاز الملحق { retainer_number } للحصول على المنتج / الخدمة.</p>
                            <p>ببساطة اضغط على الاختيار بأسفل.</p>
                            <p>{ retainer_url }</p>
                            <p>إشعر بالحرية للوصول إلى الخارج إذا عندك أي أسئلة.</p>
                            <p>شكرا لك</p>
                            <p>Regards,</p>
                            <p>{ company_name }</p>
                            <p>{ app_url }</p>',
                    'da' => '<p>Hej, { retainer_name }</p>
                            <p>Velkommen til { app_name }</p>
                            <p>H&aring;ber denne e-mail finder dig godt! Der er vedh&aelig;ftet et tilknyttet tilbageholdenummer { retainer_number } for product/service.</p>
                            <p>Klik p&aring; knappen nedenfor.</p>
                            <p>{ retainer_url }</p>
                            <p>Du er velkommen til at r&aelig;kke ud, hvis du har nogen sp&oslash;rgsm&aring;l.</p>
                            <p>Tak.</p>
                            <p>Med venlig hilsen</p>
                            <p>{ company_name }</p>
                            <p>{ app_url }</p>',
                    'de' => '<p>Hi, {retainer_name}</p>
                            <p>Willkommen bei {app_name}</p>
                            <p>Hoffe, diese E-Mail findet dich gut!! Bitte beachten Sie die beigef&uuml;gte Haltenummer {retainer_number} f&uuml;r Produkt/Service.</p>
                            <p>Klicken Sie einfach auf den Button unten.</p>
                            <p>{retainer_url}</p>
                            <p>F&uuml;hlen Sie sich frei, wenn Sie Fragen haben.</p>
                            <p>Vielen Dank,</p>
                            <p>Betrachtet,</p>
                            <p>{company_name}</p>
                            <p>{app_url}</p>',
                    'en' => '<p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hi, {retainer_name}</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Welcome to {app_name}</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Hope this email finds you well!! Please see attached retainer number {retainer_number} for product/service.</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Simply click on the button below.</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{retainer_url}</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Feel free to reach out if you have any questions.</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Thank You,</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">Regards,</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{company_name}</span></p>
                            <p style="line-height: 28px; font-family: Nunito,;"><span style="font-family: sans-serif;">{app_url}</span></p>',
                    'es' => '<p>Hi, {nombre_retain_name}</p>
                            <p>Bienvenido a {app_name}</p>
                            <p>&iexcl;Espero que este correo te encuentre bien!! Consulte el n&uacute;mero de retenci&oacute;n adjunto {retainer_number} para el producto/servicio.</p>
                            <p>Simplemente haga clic en el bot&oacute;n de abajo.</p>
                            <p>{retainer_url}</p>
                            <p>Si&eacute;ntase libre de llegar si usted tiene alguna pregunta.</p>
                            <p>Gracias,</p>
                            <p>Considerando,</p>
                            <p>{nombre_empresa}</p>
                            <p>{app_url}</p>',
                    'fr' => '<p>Salut, { payment_name }</p>
                            <p>&nbsp;</p>
                            <p>Bienvenue dans { app_name }</p>
                            <p>&nbsp;</p>
                            <p>Nous vous &eacute;crivons pour vous informer que nous avons envoy&eacute; votre paiement { payment_bill }.</p>
                            <p>&nbsp;</p>
                            <p>Nous avons envoy&eacute; votre paiement { payment_amount } pour { payment_bill } soumis &agrave; la date { payment_date } via { payment_method }.</p>
                            <p>&nbsp;</p>
                            <p>Merci beaucoup et avez un bon jour ! !!!</p>
                            <p>&nbsp;</p>
                            <p>{ company_name }</p>
                            <p>&nbsp;</p>
                            <p>{ app_url }</p>',
                    'it' => '<p>Ciao, {retainer_name}</p>
                            <p>Benvenuti in {app_name}</p>
                            <p>Spero che questa email ti trovi bene!! Si prega di consultare il numero di retainer allegato {retainer_number} per il prodotto/servizio.</p>
                            <p>Semplicemente clicca sul pulsante sottostante.</p>
                            <p>{retainer_url}</p>
                            <p>Sentiti libero di raggiungere se hai domande.</p>
                            <p>Grazie,</p>
                            <p>Riguardo,</p>
                            <p>{company_name}</p>
                            <p>{app_url}</p>',
                    'ja' => '<p>こんにちは、 {retainer_name}</p>
                            <p>{app_name} へようこそ</p>
                            <p>この E メールによりよく検出されます !! 製品 / サービスの添付された保持者番号 {retainer_number} を参照してください。</p>
                            <p>以下のボタンをクリックしてください。</p>
                            <p>{retainer_url}</p>
                            <p>質問がある場合は、自由に連絡してください。</p>
                            <p>ありがとうございます</p>
                            <p>よろしく</p>
                            <p>{ company_name}</p>
                            <p>{app_url}</p>',
                    'nl' => '<p>Hallo, { retainer_name }</p>
                            <p>Welkom bij { app_name }</p>
                            <p>Hoop dat deze e-mail je goed vindt!! Zie bijgevoegde retainer nummer { retainer_number } voor product/service.</p>
                            <p>Klik gewoon op de knop hieronder.</p>
                            <p>{ retainer_url }</p>
                            <p>Voel je vrij om uit te reiken als je vragen hebt.</p>
                            <p>Dank U,</p>
                            <p>Betreft:</p>
                            <p>{ bedrijfsnaam }</p>
                            <p>{ app_url }</p>',
                    'pl' => '<p>Witaj, {retainer_name }</p>
                            <p>Witamy w aplikacji {app_name }</p>
                            <p>Mam nadzieję, że ta wiadomość e-mail znajduje Cię dobrze!! Patrz numer przyłączonego elementu retainer {retainer_number } dla produktu/usługi.</p>
                            <p>Wystarczy kliknąć na przycisk poniżej.</p>
                            <p>{adres_url }</p>
                            <p>Czuj się swobodnie, jeśli masz jakieś pytania.</p>
                            <p>Dziękuję,</p>
                            <p>W odniesieniu do</p>
                            <p>{company_name }</p>
                            <p>{app_url }</p>',
                    'ru' => '<p>Привет, { имя_изменения }</p>
                            <p>Вас приветствует { app_name }</p>
                            <p>Надеюсь, это письмо найдет вас хорошо! См. вложенный номер { retainer_number } для product/service.</p>
                            <p>Просто нажмите на кнопку внизу.</p>
                            <p>{ retainer_url }</p>
                            <p>Не стеснитесь, если у вас есть вопросы.</p>
                            <p>Спасибо.</p>
                            <p>С уважением,</p>
                            <p>{ company_name }</p>
                            <p>{ app_url }</p>',
                    'pt' => '<p>Oi, {retainer_name}</p>
                            <p>Bem-vindo a {app_name}</p>
                            <p>Espero que este e-mail encontre voc&ecirc; bem!! Por favor, consulte o n&uacute;mero de retentor conectado {retainer_number} para produto/servi&ccedil;o.</p>
                            <p>Basta clicar no bot&atilde;o abaixo.</p>
                            <p>{retainer_url}</p>
                            <p>Sinta-se &agrave; vontade para alcan&ccedil;ar fora se voc&ecirc; tiver alguma d&uacute;vida.</p>
                            <p>Obrigado,</p>
                            <p>Considera,</p>
                            <p>{company_name}</p>
                            <p>{app_url}</p>',
                ],
            ],
            'Retainer Payment Create' => [
                'subject' => 'Retainer Payment Create',
                'variables' => '{
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "Payment Name": "payment_name",
                    "Payment Retainer": "payment_retainer",
                    "Payment Amount": "payment_amount",
                    "Payment Date": "payment_date",
                    "Payment Method": "payment_method"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبا ، { payment_name }</p>
                            <p>&nbsp;</p>
                            <p>مرحبا بك في { app_name }</p>
                            <p>&nbsp;</p>
                            <p>قمنا بالكتابة لاعلامك بأننا قد قمنا بارسال مدفوعات (payment_retainer }) الخاصة بك.</p>
                            <p>&nbsp;</p>
                            <p>لقد قمنا بارسال المبلغ ${ payment_cama } الخاص بك الى { payment_retainer } قمت بالاحالة في التاريخ { payment_date } من خلال { payment_method }.</p>
                            <p>&nbsp;</p>
                            <p>شكرا جزيلا لك وطاب يومك ! !!!</p>
                            <p>&nbsp;</p>
                            <p>{ company_name }</p>
                            <p>&nbsp;</p>
                            <p>{ app_url }</p>',
                    'da' => '',
                    'de' => '<p>Hi, {payment_name}</p>
                        <p>&nbsp;</p>
                        <p>Willkommen bei {app_name}</p>
                        <p>&nbsp;</p>
                        <p>Wir schreiben Ihnen mitzuteilen, dass wir Ihre Zahlung von {payment_&uuml;teschen} gesendet haben.</p>
                        <p>&nbsp;</p>
                        <p>Wir haben Ihre Zahlung {payment_amount} f&uuml;r {payment_&uuml;tesch}, die am Datum {payment_date} &uuml;ber {payment_method} &uuml;bergeben wurde, gesendet.</p>
                        <p>&nbsp;</p>
                        <p>Vielen Dank und haben einen guten Tag! !!!</p>
                        <p>&nbsp;</p>
                        <p>{company_name}</p>
                        <p>&nbsp;</p>
                        <p>{app_url}</p>',
                    'en' => '<p>Hi, {payment_name}</p>
                    <p>Welcome to {app_name}</p>
                    <p>We are writing to inform you that we has sent your {payment_retainer} payment.</p>
                    <p>We has sent your amount {payment_amount} payment for {payment_retainer} submited on date {payment_date} via {payment_method}.</p>
                    <p>Thank You very much and have a good day !!!!</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'es' => '<p>Hi, {nombre_pago}</p>
                    <p>&nbsp;</p>
                    <p>Bienvenido a {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Estamos escribiendo para informarle que hemos enviado su pago {payment_reten}.</p>
                    <p>&nbsp;</p>
                    <p>Hemos enviado su importe {payment_amount} pago para {payment_reten} en la fecha {payment_date} a trav&eacute;s de {payment_method}.</p>
                    <p>&nbsp;</p>
                    <p>Thank You very much and have a good day! !!!</p>
                    <p>&nbsp;</p>
                    <p>{nombre_empresa}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'fr' => '<p>Salut, { payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Bienvenue dans { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Nous vous &eacute;crivons pour vous informer que nous avons envoy&eacute; votre paiement { payment_retainer }.</p>
                    <p>&nbsp;</p>
                    <p>Nous avons envoy&eacute; votre paiement { payment_amount } pour { payment_retainer } soumis &agrave; la date { payment_date } via { payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Merci beaucoup et avez un bon jour ! !!!</p>
                    <p>&nbsp;</p>
                    <p>{ nom_entreprise }</p>
                    <p>&nbsp;</p>
                    <p>{ adresse_url }</p>',
                    'it' => '<p>Ciao, {payment_name}</p>
                    <p>&nbsp;</p>
                    <p>Benvenuti in {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Scriviamo per informarti che abbiamo inviato il tuo pagamento {payment_retainer}.</p>
                    <p>&nbsp;</p>
                    <p>Abbiamo inviato la tua quantit&agrave; {payment_amount} pagamento per {payment_retainer} subita alla data {payment_date} tramite {payment_method}.</p>
                    <p>&nbsp;</p>
                    <p>Grazie mille e buona giornata! !!!</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'ja' => '<p>こんにちは、 {payment_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_name} へようこそ</p>
                    <p>&nbsp;</p>
                    <p>{ payment_家臣} の支払いを送信したことを通知するために、この文書を作成しています。</p>
                    <p>&nbsp;</p>
                    <p>{ payment_date } には、 {payment_date } によって提出された {payment_家臣} に対する金額 {payment_金額} の支払いが送信されました。</p>
                    <p>&nbsp;</p>
                    <p>ありがとうございます。良い日をお願いします。</p>
                    <p>&nbsp;</p>
                    <p>{ company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'nl' => '<p>Hallo, { payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Welkom bij { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Wij schrijven u om u te informeren dat wij uw { payment_retainer } betaling hebben verzonden.</p>
                    <p>&nbsp;</p>
                    <p>Wij hebben uw bedrag { payment_amount } betaling voor { payment_retainer } verzonden op datum { payment_date } via { payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Hartelijk dank en hebben een goede dag! !!!</p>
                    <p>&nbsp;</p>
                    <p>{ bedrijfsnaam }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'pl' => '<p>Witaj, {payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Witamy w aplikacji {app_name }</p>
                    <p>&nbsp;</p>
                    <p>Piszemy, aby poinformować Cię, że wysłaliśmy Twoją płatność {payment_retainer }.</p>
                    <p>&nbsp;</p>
                    <p>Twoja kwota {payment_amount } została wysłana przez użytkownika {payment_retainer } w dniu {payment_date } za pomocą metody {payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Dziękuję bardzo i mam dobry dzień! !!!</p>
                    <p>&nbsp;</p>
                    <p>{company_name }</p>
                    <p>&nbsp;</p>
                    <p>{app_url }</p>',
                    'ru' => '<p>Привет, { payment_name }</p>
                    <p>&nbsp;</p>
                    <p>Вас приветствует { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Мы пишем, чтобы сообщить вам, что мы отправили вашу оплату { payment_retainer }.</p>
                    <p>&nbsp;</p>
                    <p>Мы отправили вашу сумму оплаты { payment_amoon } для { payment_retainer }, подав на дату { payment_date } через { payment_method }.</p>
                    <p>&nbsp;</p>
                    <p>Большое спасибо и хорошего дня! !!!</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'pt' => '<p>Oi, {payment_name}</p>
                    <p>&nbsp;</p>
                    <p>Bem-vindo a {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Estamos escrevendo para inform&aacute;-lo que enviamos o seu pagamento {payment_retainer}.</p>
                    <p>&nbsp;</p>
                    <p>N&oacute;s enviamos sua quantia {payment_amount} pagamento por {payment_retainer} requisitado na data {payment_date} via {payment_method}.</p>
                    <p>&nbsp;</p>
                    <p>Muito obrigado e tenha um bom dia! !!!</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',

                ],
            ],
        ];
        foreach($emailTemplate as $eTemp)
        {
            $table = EmailTemplate::where('name',$eTemp)->where('module_name','Retainer')->exists();
            if(!$table)
            {
                $emailtemplate=  EmailTemplate::create(
                    [
                        'name' => $eTemp,
                        'from' => 'Retainer',
                        'module_name' => 'Retainer',
                        'created_by' => 1,
                        'workspace_id' => 0
                    ]
                );
                foreach($defaultTemplate[$eTemp]['lang'] as $lang => $content)
                {
                    EmailTemplateLang::create(
                        [
                            'parent_id' => $emailtemplate->id,
                            'lang' => $lang,
                            'subject' => $defaultTemplate[$eTemp]['subject'],
                            'variables' => $defaultTemplate[$eTemp]['variables'],
                            'content' => $content,
                        ]
                    );
                }
            }
        }
    }
}
