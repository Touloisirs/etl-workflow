<?php

namespace Touloisirs\ETLWorkflow\Event;

class WorkflowEvent
{
    public const WORKFLOW_START = 'workflow.start';
    public const WORKFLOW_ADVANCE = 'workflow.advance';
    public const WORKFLOW_FINISH = 'workflow.finish';
}
