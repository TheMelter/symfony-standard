<?php
namespace TheMelter\BasicBundle\Twig;

class TheMelterExtension extends \Twig_Extension
{

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'maxLength'     =>  new \Twig_Filter_Method($this, 'maxLengthFilter'),
            'getClass'      =>  new \Twig_Filter_Method($this, 'getClassFilter'),
            'timePassed'    =>  new \Twig_Filter_Method($this, 'timePassedFilter'),
            'age'           =>  new \Twig_Filter_Method($this, 'ageFilter'),
            'stripTags'     =>  new \Twig_Filter_Method($this, 'stripTagsFilter'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'themelter_extension';
    }

    /**
     * Returns content with specified max length.
     */
    public function maxLengthFilter($content, $maxLength, $defaultText = "Description")
    {
        if (strlen($content) == 0)
            $content = '<i>No ' . $defaultText . ' Available Yet!</i>';
        elseif (strlen($content) > $maxLength)
            $content = trim(substr($content, 0, $maxLength)) . '...';

        return $content;
    }

    /**
     * Returns classname of documents.
     */
    public function getClassFilter($document)
    {
        $classArr = explode('\\', get_class($document));
        return end($classArr);
    }

    /**
     * Returns time passed in a readable format.
     * If precise = true, will return difference in magnitude of at least seconds.
     */
    public function timePassedFilter(\DateTime $date, $precise = false)
    {
        $now = new \DateTime('now');

        if (!$precise) {
            $now->setTime(0, 0, 0);
            $date->setTime(0, 0, 0);
            // $now->setTimeZone(new \DateTimeZone('America/Los_Angeles'));
            // $date->setTimeZone(new \DateTimeZone('America/Los_Angeles'));
            // print_r($date);
            // print_r($now);
        }

        $interval = $now->diff($date);
		$years = $interval->format('%y');
		$months = $interval->format('%m');
        $days = $interval->format('%d');
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        $seconds = $interval->format('%s');
        $sign = $interval->format('%r');

        if ($sign == "-") {
            if ($years > 0) {
                return $years == 1 ? '1 year ago' : "$years years ago";
            }
            if ($months > 0) {
                return $months == 1 ? '1 month ago' : "$months months ago";
            }
            if ($days > 0) {
                if ($days >= 21)
                    return "3 weeks ago";
                if ($days >= 14)
                    return "2 weeks ago";
                if ($days >= 7)
                    return "1 week ago";
                return $days == 1 ? 'Yesterday' : "$days days ago";
            }

            if (!$precise)
                return "Today";

            if ($hours > 1)
                return "$hours hours ago";
            if ($hours == 1)
                return "1 hour ago";
            if ($minutes > 1)
                return "$minutes minutes ago";
            if ($minutes == 1)
                return "1 minute ago";
            if ($seconds > 1)
                return "$seconds seconds ago";
            if ($seconds == 1)
                return "1 second ago";

            return "Just a moment ago";
        }
        else {
            if ($years > 0) {
                return $years == 1 ? '1 year later' : "$years years later";
            }
            if ($months > 0) {
                return $months == 1 ? '1 month later' : "$months months later";
            }
            if ($days > 0) {
                if ($days >= 21)
                    return "3 weeks later";
                if ($days >= 14)
                    return "2 weeks later";
                if ($days >= 7)
                    return "Next week";
                return $days == 1 ? 'Tomorrow' : "$days days later";
            }
            if ($days == 0) {
                if (!$precise)
                    return "Today";

                return "Just a moment ago";
            }
        }
    }

    /**
     * Returns a person's age given date of birth.
     */
    public function ageFilter(\DateTime $dob)
    {
        $difference = $dob->diff(new \DateTime);
        return $difference->y;
    }

    /**
     * Strips tags.
     */
    public function stripTagsFilter($content)
    {
        return strip_tags($content);
    }

}
