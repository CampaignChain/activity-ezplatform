<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) CampaignChain, Inc. <info@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Activity\EZPlatformBundle\Controller\REST;

use CampaignChain\CoreBundle\Controller\REST\BaseModuleController;
use CampaignChain\CoreBundle\Entity\Activity;
use CampaignChain\CoreBundle\Entity\Module;
use FOS\RestBundle\Controller\Annotations as REST;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @REST\NamePrefix("campaignchain_activity_ezplatform_rest_")
 *
 * Class ActivityController
 * @package CampaignChain\Activity\EZPlatformBundle\Controller\REST
 */
class ActivityController extends BaseModuleController
{
    const CONTROLLER_SERVICE = 'campaignchain.activity.controller.campaignchain.ezplatform.schedule';

    /**
     * Get a specific eZ Platform object.
     *
     * Example Request
     * ===============
     *
     *      GET /api/v1/p/campaignchain/activity-ezplatform/objects/42
     *
     * Example Response
     * ================
     *
    [
        {
            "location": {
                "id": 142,
                "name": "My eZ Platform object",
                "status": "unpublished",
                "createdDate": "2015-12-15T15:01:13+0000"
            }
            },
            {
                "activity": {
                "id": 42,
                "equalsOperation": true,
                "name": "My eZ Platform object",
                "startDate": "2015-12-20T12:00:00+0000",
                "status": "open",
                "createdDate": "2015-12-15T15:01:13+0000"
            }
            },
            {
                "operation": {
                "id": 127,
                "name": "My eZ Platform object",
                "startDate": "2015-12-20T12:00:00+0000",
                "status": "open",
                "createdDate": "2015-12-15T15:01:13+0000"
            }
        }
    ]
     *
     * @ApiDoc(
     *  section="Packages: eZ Platform",
     *  requirements={
     *      {
     *          "name"="id",
     *          "requirement"="\d+"
     *      }
     *  }
     * )
     *
     * @param string $id The ID of an Activity, e.g. '42'.
     *
     * @return CampaignChain\CoreBundle\Entity\Bundle
     */
    public function getObjectsAction($id)
    {
        return $this->getActivity(
            $id
        );
    }

    /**
     * Schedule an eZ Platform content object.
     *
     * Example Request
     * ===============
     *
     *      POST /api/v1/p/campaignchain/activity-ezplatform/objects
     *
     * Example Input
     * =============
     *
    {
        "activity":{
            "name":"My eZ Platform object",
            "location":100,
            "campaign":1,
            "campaignchain_hook_campaignchain_due":{
                "date":"2015-12-20T12:00:00+0000"
            },
            "campaignchain_hook_campaignchain_assignee":{
                "user":1
            }
        }
    }
     *
     * Example Response
     * ================
     *
     * See:
     *
     *      GET /api/v1/p/campaignchain/activity-ezplatform/objects/{id}
     *
     * @ApiDoc(
     *  section="Packages: eZ Platform"
     * )
     *
     * @REST\Post("/objects")
     * @ParamConverter("activity", converter="fos_rest.request_body")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postObjectsAction(Request $request, Activity $activity)
    {
        return $this->postActivity(
            'CampaignChainCoreBundle:REST/Activity:getActivities',
            $request,
            $activity
        );
    }
}