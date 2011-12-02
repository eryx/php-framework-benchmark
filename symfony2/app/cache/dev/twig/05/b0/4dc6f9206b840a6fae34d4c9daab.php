<?php

/* WebProfilerBundle:Collector:request.html.twig */
class __TwigTemplate_05b04dc6f9206b840a6fae34d4c9daab extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = array();
        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
            'menu' => array($this, 'block_menu'),
            'panel' => array($this, 'block_panel'),
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
        echo "        <img width=\"28\" height=\"28\" alt=\"Request\" style=\"border-width: 0; vertical-align: middle; margin-right: 5px;\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABOZJREFUeNrsVmsspFcY9jHMMsxKE9sxM1SUNHRpSNR9bbQqrrNUCG1E1z3ZyIpq+dEfIiToRtomEiFi/zQrWdlZFFstDYK632ZRu2yxFNu4GzOY6fPKN80YVu1m41dP8uac75zz3t/3OR+jVqv1LnLo613wuHCFnJcdTExM8LOzs+9vb28HaPZMTU1bSkpKohwcHDbPI3xwcPBMhUx+fv6V4eHhG0KhsMfd3V2srYwGfQ8MDPiWlZUtYHjZ2dlJYcBfOFK/qof6YHTs7OxsUigU4rW1tQNjY+NHpzGMjo6my2SyQJVKxcH6mzt37gRmZWXJcKQ6j0IGVcpUV1e/W1NT0y6Xyy1fNScwbCkmJuZaQkLCU11PTwspFc0lhPHW6yijQXzET3LOG9JLYWFhywiTan9//1jVisVilbe3946FhYVieXmZ297ezsN87I6hoaEqODj4BatQfh6FKlipCA8Pf1ZbW2urOQgICNjKzMxsNzExofxQVZrHx8c7FRQUXOvp6THW3CM+GCo/dw4lEsnP6+vrx6rRxsZGWVlZWcHhcH7a2tpaKCwsdElOTpbZ2tqKNjc3I5KSkj5fWVk51lLm5ubNUqk0CsudM3O4sbHxke6mj4/PYyirr6urm4+MjPy1u7v7bkpKyi9VVVV/8/l8qa+v71NdHsj5BJPwP0OKKj2BNmhwCuPs2NiYBOGyoL2DgwPzycnJT7G8y+Vyn2B+T5uHlUN3pzV7rq6uJz3EONG0s7OzXEyHKKZ1Ho93cGQZh6MODQ1dof3FxUXDUwSRHOPY2NjoiIiI22R3XFzcFyEhIT9ibfKvh6jCuY6Ojne0mYEmPsgV19nZ+Y/S0tIHQ0NDHo6OjqNOTk4j8JKP3Hyoq5DkUNEyDHMTwBEI427v7u6KDAwMFNgXgGY0jf/1/Py8pKGh4X00P18jwMrKqjE3N/dLKBJTh4CWR0ZGloqKir6Hhz6ae1FRUZsovMcI+RByzjQ1NcXv7e2ZaBuDtpIhNf2BgYFfkULHxsbGzwBROYeHh/o6KLKAy40I6zJwVLi6uhoKYW9r34EHqpycnO9aWlquA+pccK7n6em5nZqa+ufMzAwfcoU7OzsGkLWOar9Opf28q6tLoKuMRRHx3NxcyllVR3y9vb3CtLS0uoyMjKsU1vT09GZra+s2tJcFKjwVxliiX0ddXFz0SYkB+moQL4T8daBNIBDsARAGkC8F4TLtwYBZTA9Bj2DwUdG1tbU5jI+Pc0ihHJaMFRcXP0Tejg5R9nqJiYmnGkD7dE5DJBIdIGTEN15eXv4xQncEBhUVFbdQod9GR0f/MDU1ZUV7CDWvv7/fnmGtegvkBYbI1tZWLzc3t+dAGDXQxV9XIYS1mpmZMX19fSI/P7/fAQT3sT2pVCo94cHN+vr6D+DNZW2evLy8J7hbj+U9DvukrIE6URwv0HtNWO8iVJYeHh5XgZtXNIz4XrG3t7+H5RLu8TDPkzKQ0sjIqA+NzkxPT6dCoUdQUNAagUFzc/NlROQ33Kml1mC0/trIUyOWCIhtQDdAniAei5HdICnoGfu0KVlSsw+BAD0Yir6V+Pv7L5AcGMCBkTXkEDnCnPGbSL0kYuHKiBW8SlVNjC/hoeRas3zbhIisIcRzhFLMG/4vZVgFBqwyFRsJleb5Yv7/EX7T4x8BBgDTTU7fbnA/yAAAAABJRU5ErkJggg==\"/>
    ";
        $context['icon'] = new Twig_Markup(ob_get_clean());
        // line 7
        echo "    ";
        ob_start();
        // line 8
        echo "        ";
        ob_start();
        // line 9
        echo "            ";
        if ($this->getAttribute((($this->getAttribute(((array_key_exists("collector", $context)) ? (twig_default_filter($this->getContext($context, 'collector'))) : ("")), "controller", array(), "any", true)) ? (twig_default_filter($this->getAttribute($this->getContext($context, 'collector'), "controller", array(), "any", false))) : ("")), "class", array(), "any", true)) {
            // line 10
            echo "                <span>";
            echo $this->env->getExtension('code')->abbrClass($this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "controller", array(), "any", false), "class", array(), "any", false));
            echo "</span>
                <span>::</span>
                ";
            // line 12
            $context['link'] = $this->env->getExtension('code')->getFileLink($this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "controller", array(), "any", false), "file", array(), "any", false), $this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "controller", array(), "any", false), "line", array(), "any", false));
            // line 13
            echo "                <span>";
            if ($this->getContext($context, 'link')) {
                echo "<a style=\"color: #2f2f2f\" href=\"";
                echo twig_escape_filter($this->env, $this->getContext($context, 'link'), "html");
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "controller", array(), "any", false), "method", array(), "any", false), "html");
                echo "</a>";
            } else {
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "controller", array(), "any", false), "method", array(), "any", false), "html");
            }
            echo "</span>
            ";
        } else {
            // line 15
            echo "                <span>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "controller", array(), "any", false), "html");
            echo "</span>
            ";
        }
        // line 17
        echo "            <span style=\"margin: 0; padding: 0; color: #979696;\">|</span>
            <span";
        // line 18
        echo (((!$this->getAttribute($this->getContext($context, 'collector'), "route", array(), "any", false))) ? (" style=\"color:#a33\"") : (""));
        echo ">";
        echo twig_escape_filter($this->env, (($this->getAttribute($this->getContext($context, 'collector'), "route", array(), "any", false)) ? ($this->getAttribute($this->getContext($context, 'collector'), "route", array(), "any", false)) : ("NONE")), "html");
        echo "</span>
            <span style=\"margin: 0; padding: 0; color: #979696;\">|</span>
            <span style=\"color: ";
        // line 20
        echo (((200 == $this->getAttribute($this->getContext($context, 'collector'), "statuscode", array(), "any", false))) ? ("#759e1a") : ("#a33"));
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "statuscode", array(), "any", false), "html");
        echo "</span>
        ";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
        // line 22
        echo "    ";
        $context['text'] = new Twig_Markup(ob_get_clean());
        // line 23
        echo "    ";
        $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_item.html.twig")->display(array_merge($context, array("link" => $this->getContext($context, 'profiler_url'))));
    }

    // line 26
    public function block_menu($context, array $blocks = array())
    {
        // line 27
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 28
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/webprofiler/images/profiler/request.png"), "html");
        echo "\" alt=\"Request\" /></span>
    <strong>Request</strong>
</span>
";
    }

    // line 33
    public function block_panel($context, array $blocks = array())
    {
        // line 34
        echo "    <h2>Request GET Parameters</h2>

    ";
        // line 36
        if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "requestquery", array(), "any", false), "all", array(), "any", false))) {
            // line 37
            echo "        ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector'), "requestquery", array(), "any", false)));
            // line 38
            echo "    ";
        } else {
            // line 39
            echo "        <p>
            <em>No GET parameters</em>
        </p>
    ";
        }
        // line 43
        echo "
    <h2>Request POST Parameters</h2>

    ";
        // line 46
        if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "requestrequest", array(), "any", false), "all", array(), "any", false))) {
            // line 47
            echo "        ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector'), "requestrequest", array(), "any", false)));
            // line 48
            echo "    ";
        } else {
            // line 49
            echo "        <p>
            <em>No POST parameters</em>
        </p>
    ";
        }
        // line 53
        echo "
    <h2>Request Attributes</h2>

    ";
        // line 56
        if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "requestattributes", array(), "any", false), "all", array(), "any", false))) {
            // line 57
            echo "        ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector'), "requestattributes", array(), "any", false)));
            // line 58
            echo "    ";
        } else {
            // line 59
            echo "        <p>
            <em>No attributes</em>
        </p>
    ";
        }
        // line 63
        echo "
    <h2>Request Cookies</h2>

    ";
        // line 66
        if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector'), "requestcookies", array(), "any", false), "all", array(), "any", false))) {
            // line 67
            echo "        ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector'), "requestcookies", array(), "any", false)));
            // line 68
            echo "    ";
        } else {
            // line 69
            echo "        <p>
            <em>No cookies</em>
        </p>
    ";
        }
        // line 73
        echo "
    <h2>Request Headers</h2>

    ";
        // line 76
        $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector'), "requestheaders", array(), "any", false)));
        // line 77
        echo "
    <h2>Request Content</h2>

    <p>
        ";
        // line 81
        if (($this->getAttribute($this->getContext($context, 'collector'), "content", array(), "any", false) == false)) {
            // line 82
            echo "            <em>Request content not available (it was retrieved as a resource).</em>
        ";
        } elseif ($this->getAttribute($this->getContext($context, 'collector'), "content", array(), "any", false)) {
            // line 84
            echo "            <pre>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "content", array(), "any", false), "html");
            echo "</pre>
        ";
        } else {
            // line 86
            echo "            <em>No content</em>
        ";
        }
        // line 88
        echo "    </p>

    <h2>Request Server Parameters</h2>

    ";
        // line 92
        $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector'), "requestserver", array(), "any", false)));
        // line 93
        echo "
    <h2>Response Headers</h2>

    ";
        // line 96
        $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector'), "responseheaders", array(), "any", false)));
        // line 97
        echo "
    <h2>Session Attributes</h2>

    ";
        // line 100
        if (twig_length_filter($this->env, $this->getAttribute($this->getContext($context, 'collector'), "sessionattributes", array(), "any", false))) {
            // line 101
            echo "        <table>
            <tr>
                <th>Key</th>
                <th>Value</th>
            </tr>
            ";
            // line 106
            $context['attributes'] = $this->getAttribute($this->getContext($context, 'collector'), "sessionattributes", array(), "any", false);
            // line 107
            echo "            ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(twig_sort_filter(twig_get_array_keys_filter($this->getContext($context, 'attributes'))));
            foreach ($context['_seq'] as $context['_key'] => $context['key']) {
                // line 108
                echo "                <tr>
                    <th>";
                // line 109
                echo twig_escape_filter($this->env, $this->getContext($context, 'key'), "html");
                echo "</th>
                    <td>";
                // line 110
                echo twig_escape_filter($this->env, $this->env->getExtension('yaml')->dump($this->getAttribute($this->getContext($context, 'attributes'), $this->getContext($context, 'key'), array(), "array", false)), "html");
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['key'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 113
            echo "        </table>
    ";
        } else {
            // line 115
            echo "        <p>
            <em>No session attributes</em>
        </p>
    ";
        }
        // line 119
        echo "

    ";
        // line 121
        if ($this->getAttribute($this->getContext($context, 'profile'), "parent", array(), "any", false)) {
            // line 122
            echo "        <h2><a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_profiler", array("token" => $this->getAttribute($this->getAttribute($this->getContext($context, 'profile'), "parent", array(), "any", false), "token", array(), "any", false))), "html");
            echo "\">Parent request: ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'profile'), "parent", array(), "any", false), "token", array(), "any", false), "html");
            echo "</a></h2>

        ";
            // line 124
            $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, 'profile'), "parent", array(), "any", false), "getcollector", array("request", ), "method", false), "requestattributes", array(), "any", false)));
            // line 125
            echo "    ";
        }
        // line 126
        echo "
    ";
        // line 127
        if (twig_length_filter($this->env, $this->getAttribute($this->getContext($context, 'profile'), "children", array(), "any", false))) {
            // line 128
            echo "        <h2>Sub requests</h2>

        ";
            // line 130
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, 'profile'), "children", array(), "any", false));
            foreach ($context['_seq'] as $context['_key'] => $context['child']) {
                // line 131
                echo "            <h3><a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_profiler", array("token" => $this->getAttribute($this->getContext($context, 'child'), "token", array(), "any", false))), "html");
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'child'), "token", array(), "any", false), "html");
                echo "</a></h3>
            ";
                // line 132
                $this->env->loadTemplate("WebProfilerBundle:Profiler:bag.html.twig")->display(array("bag" => $this->getAttribute($this->getAttribute($this->getContext($context, 'child'), "getcollector", array("request", ), "method", false), "requestattributes", array(), "any", false)));
                // line 133
                echo "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 134
            echo "    ";
        }
        // line 135
        echo "
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:request.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
