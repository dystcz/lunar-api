<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use LaravelJsonApi\Core\Document\Link;
use LaravelJsonApi\Core\Document\Links;

class OrderResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        return parent::attributes($request);
    }

    // /**
    //  * Get the resource's `self` link URL.
    //  */
    // public function selfUrl(): string
    // {
    //     if ($this->selfUri) {
    //         return $this->selfUri;
    //     }
    //
    //     return $this->selfUri = URL::signedRoute(
    //         'v1.orders.show',
    //         ['order' => $this->id()],
    //     );
    // }

    // /**
    //  * Get the resource's links.
    //  *
    //  * @param  \Illuminate\Http\Request|null  $request
    //  */
    // public function links($request): Links
    // {
    //     return new Links(
    //         $this->selfLink(),
    //
    //         new Link(
    //             'self.signed',
    //             URL::signedRoute(
    //                 'v1.orders.show',
    //                 ['order' => $this->id()],
    //             ),
    //         ),
    //         new Link(
    //             'create-payment-intent.signed',
    //             URL::signedRoute(
    //                 'v1.orders.createPaymentIntent',
    //                 ['order' => $this->id()],
    //             ),
    //         ),
    //         new Link(
    //             'mark-order-pending-payment.signed',
    //             URL::signedRoute(
    //                 'v1.orders.markPendingPayment',
    //                 ['order' => $this->id()],
    //             ),
    //         ),
    //         new Link(
    //             'mark-order-awaiting-payment.signed',
    //             URL::signedRoute(
    //                 'v1.orders.markAwaitingPayment',
    //                 ['order' => $this->id()],
    //             ),
    //         ),
    //         new Link(
    //             'check-order-payment-status.signed',
    //             URL::signedRoute(
    //                 'v1.orders.checkOrderPaymentStatus',
    //                 ['order' => $this->id()],
    //             ),
    //         ),
    //     );
    // }
}
