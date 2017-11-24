<?php

namespace SpendTracker\Http\Controllers;

use Illuminate\Http\Request;
use SpendTracker\Http\Controllers\Base\AbstractController;
use SpendTracker\Libraries\TransactionFactory;
use SpendTracker\Libraries\TransactionImporter;
use SpendTracker\Models\Card;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use View;

class ImportController extends AbstractController
{
    public function index()
    {
        $cards = Card::orderBy('name')->get();

        return View::make(
            'import-index',
            [
                'cards' => $cards,
            ]
        );
    }

    public function getImport(Card $card)
    {
        return View::make(
            'import',
            [
                'card' => $card,
            ]
        );
    }

    public function postImport(
        Card $card,
        Request $request,
        TransactionFactory $transactionFactory,
        TransactionImporter $transactionImporter
    ) {
        $results = [];

        if ($request->has('files')) {
            $files = $request->files->get('files');
            foreach ($files as $file) {
                if (!$file instanceof UploadedFile) {
                    throw new BadRequestHttpException();
                }

                $contents = file_get_contents($file->getPathname());

                $transactions = $transactionFactory->createTransactions($card, $contents);

                ob_start();
                $transactionImporter->import($transactions);
                $result = ob_get_contents();
                ob_end_clean();

                $results[$file->getClientOriginalName()] = $result;
            }
        }

        return View::make(
            'import-results',
            [
                'card' => $card,
                'results' => $results
            ]
        );
    }
}
