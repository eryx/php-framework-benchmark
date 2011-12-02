<?php

/* WebProfilerBundle:Profiler:toolbar_item.html.twig */
class __TwigTemplate_b52d8450198045d23d00ca48466da997 extends Twig_Template
{
    protected function doDisplay(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        if ($this->getContext($context, 'link')) {
            // line 2
            echo "    ";
            ob_start();
            // line 3
            echo "        <a style=\"text-decoration: none; margin: 0; padding: 0;\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_profiler", array("token" => $this->getContext($context, 'token'), "panel" => $this->getContext($context, 'name'))), "html");
            echo "\">";
            echo twig_escape_filter($this->env, $this->getContext($context, 'icon'), "html");
            echo "</a>
    ";
            $context['icon'] = new Twig_Markup(ob_get_clean());
        }
        // line 6
        echo "<span style=\"white-space:nowrap; color:#2f2f2f; display:inline-block; min-height:24px; border-right:1px solid #cdcdcd; padding:5px 7px 5px 4px; \">
     ";
        // line 7
        echo twig_escape_filter($this->env, ((array_key_exists("icon", $context)) ? (twig_default_filter($this->getContext($context, 'icon'), "")) : ("")), "html");
        echo "
     ";
        // line 8
        echo twig_escape_filter($this->env, ((array_key_exists("text", $context)) ? (twig_default_filter($this->getContext($context, 'text'), "")) : ("")), "html");
        echo "
</span>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:toolbar_item.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
