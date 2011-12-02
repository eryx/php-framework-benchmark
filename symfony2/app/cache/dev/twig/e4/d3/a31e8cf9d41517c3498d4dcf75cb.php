<?php

/* SensioDistributionBundle::Configurator/steps.html.twig */
class __TwigTemplate_e4d3a31e8cf9d41517c3498d4dcf75cb extends Twig_Template
{
    protected function doDisplay(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<div class=\"symfony-block-steps\">
    ";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(range(1, $this->getContext($context, 'count')));
        foreach ($context['_seq'] as $context['_key'] => $context['i']) {
            // line 3
            echo "
      ";
            // line 4
            if (($this->getContext($context, 'i') == ($this->getContext($context, 'index') + 1))) {
                // line 5
                echo "          <span class=\"selected\">Step ";
                echo twig_escape_filter($this->env, $this->getContext($context, 'i'), "html");
                echo "</span>
      ";
            } else {
                // line 7
                echo "          <span>Step ";
                echo twig_escape_filter($this->env, $this->getContext($context, 'i'), "html");
                echo "</span>
      ";
            }
            // line 9
            echo "
      ";
            // line 10
            if (($this->getContext($context, 'i') != $this->getContext($context, 'count'))) {
                // line 11
                echo "        &gt;
      ";
            }
            // line 13
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 14
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "SensioDistributionBundle::Configurator/steps.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
