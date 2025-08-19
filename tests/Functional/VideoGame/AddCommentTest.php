<?php

namespace App\Tests\Functional\VideoGame;

use App\Tests\Functional\FunctionalTestCase;

class AddCommentTest extends FunctionalTestCase {

    public function testComment(){

        // L'uttilisateur se connecte
        $this->login();
    
        // Il atteins la page du jeu 19
        $this->get('/jeu-video-19');
    
        // Il ouvre la section avis
        // Il obtiens un formualire avec...    
        $FORM_SELECTOR = 'form[name=review]';
    
        // - Un dropdown note
        self::assertSelectorCount(1, "$FORM_SELECTOR select[name='review[rating]']");
    
        // - Un champ commentaire
        self::assertSelectorCount(1, "$FORM_SELECTOR textarea[name='review[comment]']");
    
        // - Un bouton de soumission
        self::assertSelectorCount(1, "$FORM_SELECTOR button[type='submit']");
    
        // Il entre une nouvelle note
        $this->client->submitForm(
            'Poster',
            [
                'review[rating]' => 5,
                'review[comment]' => 'Ceci est un commentaire de test.'
            ],
            'POST'
        );

        // (follow redirection)
        $response = $this->client->getResponse();
        file_put_contents('debug-output.html', $response->getContent());
        self::assertTrue($response->isRedirect(), 'La réponse n’est pas une redirection.');
        $this->client->followRedirect();
        // exit;
    
        // Son commentaire est pris en compte et affiché dans la section avis
        file_put_contents('test-output.html', $this->client->getResponse()->getContent());
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains(
            'div.list-group-item p',
            'Ceci est un commentaire de test.'
        );

    }

}