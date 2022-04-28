<?php

namespace TLBM\Session;

use Exception;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\ApiUtils\Contracts\TimeUtilsInterface;
use TLBM\Entity\Session;
use TLBM\Repository\Contracts\ORMInterface;

class SessionManager
{
    /**
     * @var ORMInterface
     */
    protected ORMInterface $repository;

    /**
     * @var TimeUtilsInterface
     */
    protected TimeUtilsInterface $timeUtils;

    /**
     * @var ?Session
     */
    protected ?Session $currentSession = null;

	/**
	 * @var SanitizingInterface
	 */
	protected SanitizingInterface $sanitizing;

	/**
	 * @var EscapingInterface
	 */
	protected EscapingInterface $escaping;

	/**
	 * @param EscapingInterface $escaping
	 * @param SanitizingInterface $sanitizing
	 * @param ORMInterface $repository
	 * @param TimeUtilsInterface $timeUtils
	 */
    public function __construct(EscapingInterface $escaping, SanitizingInterface $sanitizing, ORMInterface $repository, TimeUtilsInterface $timeUtils)
    {
        $this->repository = $repository;
        $this->timeUtils  = $timeUtils;
		$this->sanitizing = $sanitizing;
		$this->escaping = $escaping;

        $this->deleteExpiredSessions();
        $this->currentSession = $this->getCurrentSession();

        if ($this->currentSession != null) {
            $this->setSessionCookie($this->currentSession);
        }
    }

    public function deleteExpiredSessions()
    {
        $em       = $this->repository->getEntityManager();
        $time     = $this->timeUtils->time();
        $q        = $em->createQuery("SELECT s FROM TLBM\Entity\Session s where s.sessionExpiryTimestamp < " . $time);
        $sessions = $q->getResult();
        foreach ($sessions as $session) {
            try {
                $em->remove($session);
            } catch (Exception $e) {
                if (WP_DEBUG) {
                    echo $this->escaping->escHtml($e->getMessage());
                }
            }
        }
    }

    /**
     * @return Session|null
     */
    public function getCurrentSession(): ?Session
    {
        if ($this->currentSession != null) {
            return $this->currentSession;
        }

        $sessKey = $this->getCurrentSessionKey();
        if ($sessKey) {
            $em      = $this->repository->getEntityManager();
            $session = $em->getRepository(Session::class)->findOneBy(["sessionKey" => $sessKey]);
            if ($session instanceof Session) {
                $this->currentSession = $session;

                return $session;
            }
        }

        return $this->createSession();
    }

    /**
     * @return string|null
     */
    private function getCurrentSessionKey(): ?string
    {
        if (isset($_REQUEST['BM_SESSION']) && !empty($_REQUEST['BM_SESSION'])) {
            return $this->sanitizing->sanitizeKey($_REQUEST['BM_SESSION']);
        }

        return null;
    }

    /**
     * @return Session|null
     */
    public function createSession(): ?Session
    {
        $session = new Session();
        $session->setSessionExpiryTimestamp($this->getNextExpiryTimestamp());

        try {
            $session->setSessionKey(bin2hex(random_bytes(20)));
        } catch (Exception $e) {
            $session->setSessionKey(md5(time() + rand()));
        }

        try {
            $this->repository->getEntityManager()->persist($session);
        } catch (Exception $e) {
            if (WP_DEBUG) {
                echo $this->escaping->escHtml($e->getMessage());
            }

            return null;
        }

        $this->setSessionCookie($session);

        return $session;
    }

    public function getNextExpiryTimestamp()
    {
        return $this->timeUtils->time() + 60 * 30;
    }

    public function setSessionCookie(Session $session)
    {
        setcookie("BM_SESSION", $session->getSessionKey(), $session->getSessionExpiryTimestamp());
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getValue(string $key)
    {
        $session = $this->getCurrentSession();
        if ($session) {
            return $session->getSingleValue($key);
        }

        return null;
    }

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
    public function removeValue(string $key): bool {
        $session = $this->getCurrentSession();
        $em      = $this->repository->getEntityManager();
        if ($session) {
            $session->removeSingleValue($key);
            $session->setSessionExpiryTimestamp($this->getNextExpiryTimestamp());
            try {
                $em->persist($session);
                $em->flush();
				return true;
            } catch (Exception $e) {
                if (WP_DEBUG) {
                    echo $this->escaping->escHtml($e->getMessage());
                }
            }
        }

		return false;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function setValue(string $key, $value): bool
    {
        $session = $this->getCurrentSession();
        $em      = $this->repository->getEntityManager();
        if ($session) {
            $session->setSingleValue($key, $value);
            $session->setSessionExpiryTimestamp($this->getNextExpiryTimestamp());
            try {
                $em->persist($session);
                $em->flush();
            } catch (Exception $e) {
                if (WP_DEBUG) {
                    echo $this->escaping->escHtml($e->getMessage());
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getFormInputContent(): string
    {
        $session = $this->getCurrentSession();
        if ($session) {
            return "<input type='hidden' name='BM_SESSION' value='" . $session->getSessionKey() . "'>";
        }

        return "";
    }
}