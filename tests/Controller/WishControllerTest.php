<?php

namespace App\Test\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WishControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private WishRepository $repository;
    private string $path = '/wish/controller2/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Wish::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Wish index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'wish[title]' => 'Testing',
            'wish[description]' => 'Testing',
            'wish[author]' => 'Testing',
            'wish[isPublished]' => 'Testing',
            'wish[dateCreated]' => 'Testing',
        ]);

        self::assertResponseRedirects('/wish/controller2/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Wish();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setAuthor('My Title');
        $fixture->setIsPublished('My Title');
        $fixture->setDateCreated('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Wish');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Wish();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setAuthor('My Title');
        $fixture->setIsPublished('My Title');
        $fixture->setDateCreated('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'wish[title]' => 'Something New',
            'wish[description]' => 'Something New',
            'wish[author]' => 'Something New',
            'wish[isPublished]' => 'Something New',
            'wish[dateCreated]' => 'Something New',
        ]);

        self::assertResponseRedirects('/wish/controller2/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getAuthor());
        self::assertSame('Something New', $fixture[0]->getIsPublished());
        self::assertSame('Something New', $fixture[0]->getDateCreated());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Wish();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setAuthor('My Title');
        $fixture->setIsPublished('My Title');
        $fixture->setDateCreated('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/wish/controller2/');
    }
}
