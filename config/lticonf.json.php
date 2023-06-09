<?php
    header('Content-Type: application/json; charset=UTF-8');
    echo'
    {
  "title":"GP WhaleSong",
  "description":"LTI 1.3 Test Tool used for GP dev purposes",
  "oidc_initiation_url":"https://cobrien2.greenriverdev.com/whalesong/oidc_login/index.php",
  "target_link_uri":"https://cobrien2.greenriverdev.com/whalesong/oidc_login/authLogin.php",
  "scopes":[
    "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem",
    "https://purl.imsglobal.org/spec/lti-ags/scope/result.readonly",
    "https://purl.imsglobal.org/spec/lti-ags/scope/result.readonly",
    "https://purl.imsglobal.org/spec/lti-ags/scope/score",
    "https://canvas.instructure.com/lti-ags/progress/scope/show",
    "https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly"
  ],
  "extensions":[
    {
      "domain":"cobrien2.greenriverdev.com",
      "tool_id":"guided-practice",
      "platform":"canvas.instructure.com",
      "privacy_level":"public",
      "settings":{
        "text":"Launch Guided Practice Tool",
        "icon_url":"https://cobrien2.greenriverdev.com/whalesong/toolicon/orcas-logo.png",
        "selection_height": 800,
        "selection_width": 800,
        "placements":[
          {
            "text":"Guided Practice - Course Nav",
            "enabled": true,
            "icon_url":"https://cobrien2.greenriverdev.com/whalesong/toolicon/orcas-logo.png",
            "placement":"course_navigation",
            "message_type":"LtiResourceLinkRequest",
            "target_link_uri":"https://cobrien2.greenriverdev.com/whalesong/pages/coursenav/index.php",
            "canvas_icon_class":"icon-lti",
            "custom_fields":{
              "canvas_course_id":"$Canvas.user.id"
            }
          },
          {
            "text":"Guided Practice - Editor",
            "enabled": true,
            "icon_url":"https://cobrien2.greenriverdev.com/whalesong/toolicon/orcas-logo.png",
            "placement":"editor_button",
            "message_type":"LtiDeepLinkingRequest",
            "target_link_uri":"https://cobrien2.greenriverdev.com/whalesong/pages/editorbutton/index.php",
            "selection_height": 500,
            "selection_width": 500
          },
          {
            "text":"Guided Practice - Assignment",
            "enabled":true,
            "icon_url":"https://cobrien2.greenriverdev.com/whalesong/toolicon/orcas-logo.png",
            "placement":"assignment_selection",
            "message_type":"LtiDeepLinkingRequest",
            "target_link_uri":"https://cobrien2.greenriverdev.com/whalesong/pages/assignmentlink/index.php"
          }
        ]
      }
    }
  ],
  "public_jwk_url": "https://cobrien2.greenriverdev.com/whalesong/db_comms/keys/public.key",
  "custom_fields":{
    "canvas_course_id":"$Canvas.user.sisid"
  }
}';