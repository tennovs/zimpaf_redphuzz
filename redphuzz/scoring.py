class ScoringFormula():
    def calculate_score(self, candidate):
        pass
    def calculate_priority(self, candidate):
        pass
    def calculate_energy(self, candidate):
        pass
    def calculate_priority_and_score(self, candidate): #added by tennov
        pass


class DefaultScoringFormula(ScoringFormula):
    def calculate_score(self, candidate):
        hit_counter=0
        for path in candidate.new_paths:
            filename, lines = path.split('::::')
            hit_counter += lines.count("_")

        return hit_counter + len(candidate.paths)

    def calculate_priority(self, candidate):
        return self.calculate_score(candidate) #in current implementation, priority is same as score

    def calculate_energy(self, candidate):
        if candidate.parent is not None:
            energy = max(1, candidate.parent.number_of_new_paths + abs(candidate.parent.score - candidate.score))
        else:
            energy = max(1, len(candidate.new_paths))
        return energy 


#autor: tennov
#The code below calculates score based on the function traces collected by zimpaf extension
from libzimpaf.constants import (APICallTraceStatus, CandidatePriority, SoftwareFlaw, Vulnerability,
                                 Key)
import libzimpaf.function_traces as func_trace
from libzimpaf.constants import VulnFuncStatus, NumIterationsThreshold

class FunctionTracesBasedScoring(ScoringFormula):                
    #the default function used to calculate priority and score 
    def calculate_priority_and_score(self, c, logic_vuln_check=False): #c is candidate
        apicallstatus = c.api_call_status
        num_vf_high_priority = 0
        num_vf_medium_priority = 0
        num_vf_low_priority = 0
        total_vf_num_iterations = 0
           
        if c.vuln_functions: #vuln funcs 
            for func in c.vuln_functions:
                if func[Key.NUM_FUNC_ITERATIONS] < NumIterationsThreshold.LOW:
                    num_vf_high_priority += 1
                elif func[Key.NUM_FUNC_ITERATIONS] < NumIterationsThreshold.MEDIUM:
                    num_vf_medium_priority +=1
                elif func[Key.NUM_FUNC_ITERATIONS] < NumIterationsThreshold.HIGH:
                    num_vf_low_priority += 1
                total_vf_num_iterations += func[Key.NUM_FUNC_ITERATIONS]

            if num_vf_high_priority and not (apicallstatus & APICallTraceStatus.EXIST_SANITATION_FUNCTION):  #no sanitation
                c.priority = CandidatePriority.HIGH
                c.score = num_vf_high_priority * 2
            elif num_vf_high_priority and (apicallstatus & APICallTraceStatus.EXIST_SANITATION_FUNCTION):  #no sanitation
                c.priority = CandidatePriority.HIGH
                c.score = num_vf_high_priority
            elif num_vf_medium_priority and not (apicallstatus & APICallTraceStatus.EXIST_SANITATION_FUNCTION):  #no sanitation
                c.priority = CandidatePriority.MEDIUM
                c.score = (num_vf_medium_priority * 2 + 
                          (len(c.vuln_functions) * NumIterationsThreshold.HIGH - total_vf_num_iterations))
            elif num_vf_medium_priority and (apicallstatus & APICallTraceStatus.EXIST_SANITATION_FUNCTION):
                c.priority = CandidatePriority.MEDIUM
                c.score = (num_vf_medium_priority * 2 + 
                          (len(c.vuln_functions) * NumIterationsThreshold.HIGH - total_vf_num_iterations) -
                           len(c.sanit_functions)) #plus #sanit_functions absent
            else:
                c.priority = CandidatePriority.LOW
                c.score = num_vf_low_priority + c.number_of_new_paths
        elif apicallstatus & APICallTraceStatus.EXIST_DIE_EXIT_FUNCTION: #very likely exception has occured means many paths left unexplored
            if c.num_iterations <= NumIterationsThreshold.LOW:           #only reachable when there is no vuln functions             
                c.priority =  CandidatePriority.MEDIUM
                c.score = NumIterationsThreshold.HIGH - c.num_iterations
            else:
                c.priority = CandidatePriority.LOW
                c.score = c.number_of_new_paths
        elif apicallstatus & APICallTraceStatus.EXIST_SANITATION_FUNCTION: #may be there are vuln functions not in our list
            if c.num_iterations <= NumIterationsThreshold.LOW:            
                c.priority = CandidatePriority.MEDIUM
                c.score = NumIterationsThreshold.HIGH - c.num_iterations
            else:
                c.priority = CandidatePriority.LOW
                c.score = c.number_of_new_paths
          
