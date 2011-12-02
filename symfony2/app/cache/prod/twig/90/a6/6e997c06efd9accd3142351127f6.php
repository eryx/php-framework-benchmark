<?php

/* TwigBundle:Exception:traces.html.twig */
class __TwigTemplate_90a66e997c06efd9accd3142351127f6 extends Twig_Template
{
    protected function doDisplay(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<div class=\"block\">
    ";
        // line 2
        if (($this->getContext($context, 'count') > 0)) {
            // line 3
            echo "        <h2>
            <span><small>[";
            // line 4
            echo twig_escape_filter($this->env, (($this->getContext($context, 'count') - $this->getContext($context, 'position')) + 1), "html");
            echo "/";
            echo twig_escape_filter($this->env, ($this->getContext($context, 'count') + 1), "html");
            echo "]</small></span>
            ";
            // line 5
            echo $this->env->getExtension('code')->abbrClass($this->getAttribute($this->getContext($context, 'exception'), "class", array(), "any", false));
            echo ": ";
            echo twig_escape_filter($this->env, twig_strtr($this->getAttribute($this->getContext($context, 'exception'), "message", array(), "any", false), array("
" => "<br />")), "html");
            echo "&nbsp;
            ";
            // line 6
            ob_start();
            // line 7
            echo "            <a href=\"#\" onclick=\"toggle('traces_";
            echo twig_escape_filter($this->env, $this->getContext($context, 'position'), "html");
            echo "', 'traces'); switchIcons('icon_traces_";
            echo twig_escape_filter($this->env, $this->getContext($context, 'position'), "html");
            echo "_open', 'icon_traces_";
            echo twig_escape_filter($this->env, $this->getContext($context, 'position'), "html");
            echo "_close'); return false;\">
                <img class=\"toggle\" id=\"icon_traces_";
            // line 8
            echo twig_escape_filter($this->env, $this->getContext($context, 'position'), "html");
            echo "_close\" alt=\"-\" src=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/framework/images/blue_picto_less.gif"), "html");
            echo "\" style=\"visibility: ";
            echo (((0 == $this->getContext($context, 'count'))) ? ("display") : ("hidden"));
            echo "\" />
                <img class=\"toggle\" id=\"icon_traces_";
            // line 9
            echo twig_escape_filter($this->env, $this->getContext($context, 'position'), "html");
            echo "_open\" alt=\"+\" src=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/framework/images/blue_picto_more.gif"), "html");
            echo "\" style=\"visibility: ";
            echo (((0 == $this->getContext($context, 'count'))) ? ("hidden") : ("display"));
            echo "; margin-left: -18px\" />
            </a>
            ";
            echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
            // line 12
            echo "        </h2>
    ";
        } else {
            // line 14
            echo "        <h2>Stack Trace</h2>
    ";
        }
        // line 16
        echo "
    <a id=\"traces_link_";
        // line 17
        echo twig_escape_filter($this->env, $this->getContext($context, 'position'), "html");
        echo "\"></a>
    <ol class=\"traces list_exception\" id=\"traces_";
        // line 18
        echo twig_escape_filter($this->env, $this->getContext($context, 'position'), "html");
        echo "\" style=\"display: ";
        echo (((0 == $this->getContext($context, 'count'))) ? ("block") : ("none"));
        echo "\">
        ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, 'exception'), "trace", array(), "any", false));
        foreach ($context['_seq'] as $context['i'] => $context['trace']) {
            // line 20
            echo "            <li>
                ";
            // line 21
            $this->env->loadTemplate("TwigBundle:Exception:trace.html.twig")->display(array("prefix" => $this->getContext($context, 'position'), "i" => $this->getContext($context, 'i'), "trace" => $this->getContext($context, 'trace')));
            // line 22
            echo "            </li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['i'], $context['trace'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 24
        echo "    </ol>
</div>
";
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:traces.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
