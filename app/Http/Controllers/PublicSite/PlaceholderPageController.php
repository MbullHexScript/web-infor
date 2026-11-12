<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PlaceholderPageController extends Controller
{
    public function __invoke(string $locale, string $path): Response
    {
        abort_unless(in_array($locale, ['id', 'en'], true), 404);

        return Inertia::render('Public/Placeholder', [
            'locale' => $locale,
            'path' => $path,
        ]);
    }
}
