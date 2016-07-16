<?php
/*
 * Copyright 2016 CampaignChain, Inc. <info@campaignchain.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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