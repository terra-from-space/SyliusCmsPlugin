<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCmsPlugin\Twig\Runtime;

use BitBag\SyliusCmsPlugin\DataCollector\MediaRenderingEventRecorderInterface;
use BitBag\SyliusCmsPlugin\Resolver\MediaProviderResolverInterface;
use BitBag\SyliusCmsPlugin\Resolver\MediaResourceResolverInterface;

final class RenderMediaRuntime implements RenderMediaRuntimeInterface
{
    /** @var MediaProviderResolverInterface */
    private $mediaProviderResolver;

    /** @var MediaResourceResolverInterface */
    private $mediaResourceResolver;

    /** @var MediaRenderingEventRecorderInterface */
    private $mediaRenderingEventRecorder;

    public function __construct(
        MediaProviderResolverInterface $mediaProviderResolver,
        MediaResourceResolverInterface $mediaResourceResolver,
        MediaRenderingEventRecorderInterface $mediaRenderingEventRecorder
    ) {
        $this->mediaProviderResolver = $mediaProviderResolver;
        $this->mediaResourceResolver = $mediaResourceResolver;
        $this->mediaRenderingEventRecorder = $mediaRenderingEventRecorder;
    }

    public function renderMedia(string $code, ?string $template = null): string
    {
        $media = $this->mediaResourceResolver->findOrLog($code);

        if (null !== $media) {
            $this->mediaRenderingEventRecorder->recordRenderingMediaEvents($media);

            return $this->mediaProviderResolver->resolveProvider($media)->render($media, $template);
        }

        return '';
    }
}
