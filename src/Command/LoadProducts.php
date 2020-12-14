<?php

namespace App\Command;

use App\Entity\Product;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class LoadProducts extends Command
{
    protected static $defaultName = 'app:load-products';
    protected static $filename = 'dummy.json';
    private $appKernel;
    private $container;

    public function __construct(KernelInterface $app_kernel, ContainerInterface $container)
    {
        parent::__construct();
        $this->appKernel = $app_kernel;
        $this->container = $container;
    }

    protected function configure()
    {
        $this->setDescription('Loads the preset dummy products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Product loader',
            '============',
            '',
        ]);

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Do you really want to load the preset products?',
            ['yes', 'no'],
            1
        );

        $answer = $helper->ask($input, $output, $question);

        if ($answer === "no") {
            $output->writeln([
                '',
                'Exit.',
            ]);

            return Command::SUCCESS;
        }

        if ($this->load()) {
            $output->writeln([
                '',
                'Error.',
            ]);

            return Command::FAILURE;
        }

        $output->writeln([
            '',
            'Success.',
        ]);
        return Command::SUCCESS;
    }

    private function load(): int
    {
        try {
            $projectRoot = $this->appKernel->getProjectDir();
            $data = file_get_contents($projectRoot . DIRECTORY_SEPARATOR . self::$filename);
            $data = json_decode($data, true);

            $this->save($data);
        } catch (\Exception $e) {
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }

    private function save($data): void
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        foreach ($data as $row) {
            $product = new Product();
            $product->setName($row["name"]);
            $product->setPrice($row["price"]);
            $product->setPurchased($row["purchased"]);
            $product->setCreatedAt(new \DateTime($row["createdAt"]));

            $entityManager->persist($product);
        }

        $entityManager->flush();
    }
}