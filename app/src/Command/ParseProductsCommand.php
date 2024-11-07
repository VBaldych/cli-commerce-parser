<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\ProductMessage;
use App\Service\Factory\ParserFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:parse-products',
    description: 'Parse products from online shop.',
    aliases: ['app:pp'],
)]
class ParseProductsCommand extends Command
{
    public function __construct(
        private readonly ParserFactory $parserFactory,
        private readonly MessageBusInterface $messageBus,
        private readonly array $urls
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // Currently, we use only specific shop.
            // We can make this call more dynamic if we going to add more
            // shops in future.
            $parser = $this->parserFactory->getParser('Rozetka');
            $io = new SymfonyStyle($input, $output);

            $io->title('Fetching products...');
            $io->progressStart(100);

            foreach ($this->urls as $url) {
                // Parse products.
                $products = $parser->parse($url);

                foreach ($products as $product) {
                    // Process data in queues.
                    $this->messageBus->dispatch(new ProductMessage($product));
                }
            }

            $io->progressFinish();

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $outputStyle = new OutputFormatterStyle('red', '#ff0', ['bold', 'blink']);
            $output->getFormatter()->setStyle('fire', $outputStyle);
            $output->writeln('Something Went Wrong [' . $exception->getMessage() . ']');

            return Command::FAILURE;
        }
    }
}
