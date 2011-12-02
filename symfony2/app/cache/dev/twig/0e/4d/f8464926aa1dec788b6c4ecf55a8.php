<?php

/* WebProfilerBundle:Collector:timer.html.twig */
class __TwigTemplate_0e4df8464926aa1dec788b6c4ecf55a8 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = array();
        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
        );
    }

    public function getParent(array $context)
    {
        $parent = "WebProfilerBundle:Profiler:layout.html.twig";
        if ($parent instanceof Twig_Template) {
            $name = $parent->getTemplateName();
            $this->parent[$name] = $parent;
            $parent = $name;
        } elseif (!isset($this->parent[$parent])) {
            $this->parent[$parent] = $this->env->loadTemplate($parent);
        }

        return $this->parent[$parent];
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_toolbar($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        ob_start();
        // line 5
        echo "        <img width=\"16\" height=\"28\" alt=\"Timers\" style=\"vertical-align: middle; margin-right: 5px;\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAcCAYAAABoMT8aAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAiNJREFUeNpi/P//PwMlgImBQjDwBrCcO3cOq0RRUdF3ZH5fXx8nTVzAePbsWcq8gMwxMjJiSUlJcXv9+nXm169fbf78+SMAVsTC8paXl3ePmJjYjJkzZx4GevsviheAGhmBguL+/v4779y5s/Xjx48+MM0gAGQLv3//PvzmzZv7AwMD19y+fVsEpAfsBWBCYly8eLHcsmXLjnz//l2GGGcDXXM1IyPD2dvb+xXIBTwbN25chU3zgQMHwBgdfP78WXvp0qVzgUwuprq6utg3b96YkRp4z549854wYYI7071791LJjYFLly7lM7148UKHXAOALtdnAYYwCyGFyOHg4OAAZ3/69ImfopTIzMz8j4WVlfXf79+/sRqEbBs2wMfH94tJXV39DbkuUFFReclkb29/jlwDPD09jzGFhoZu0NTU/EKqZktLyzdOTk7bQX4/U1tbu1pcXPwvsZoVFBR+lZeXLwUyz4MMuCMlJbWmv79/o56e3k9Cms3MzL5PmjRphYCAwCYg9wE4MwEZwkBsDsReO3fudN+zZ4/shQsX2ICxA9bEzs7OYGBg8NPHx+eBra3tdqDQVpDLgfgjuEABZk2QS3hBAQvExkBsAHIpMAsLAOP6PzC63gP590FOBmJQCXQPiL8Ai4D/KCUS0CBWIAUqB8SAWAiIQeUgqOIAlY/vgPgVEH8AavyDtUQCSoDc/BqEoQUGLIH9A9mGtUwc8JoJIMAAS9XemfR7crQAAAAASUVORK5CYII=\"/>
    ";
        $context['icon'] = new Twig_Markup(ob_get_clean());
        // line 7
        echo "    ";
        ob_start();
        // line 8
        echo "        ";
        echo twig_escape_filter($this->env, sprintf("%.0f", ($this->getAttribute($this->getContext($context, 'collector'), "time", array(), "any", false) * 1000)), "html");
        echo " ms
    ";
        $context['text'] = new Twig_Markup(ob_get_clean());
        // line 10
        echo "    ";
        $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_item.html.twig")->display(array_merge($context, array("link" => false)));
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:timer.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
